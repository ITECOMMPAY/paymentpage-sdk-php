<?php

namespace ecommpay;

/**
 * Callback
 */
class Callback
{
    /**
     * Successful payment
     */
    const SUCCESS_STATUS = 'success';

    /**
     * Rejected payment
     */
    const DECLINE_STATUS = 'decline';

    /**
     * Awaiting a request with the result of a 3-D Secure Verification
     */
    const AW_3DS_STATUS = 'awaiting 3ds result';

    /**
     * Awaiting customer return after redirecting the customer to an external provider system
     */
    const AW_RED_STATUS = 'awaiting redirect result';

    /**
     * Awaiting customer actions, if the customer may perform additional attempts to make a payment
     */
    const AW_CUS_STATUS = 'awaiting customer';

    /**
     * Awaiting additional parameters
     */
    const AW_CLA_STATUS = 'awaiting clarification';

    /**
     * Awaiting request for withdrawal of funds (capture) or cancellation of payment (cancel) from your project
     */
    const AW_CAP_STATUS = 'awaiting capture';

    /**
     * Holding of funds (produced on authorization request) is cancelled
     */
    const CANCELLED_STATUS = 'cancelled';

    /**
     * Successfully completed the full refund after a successful payment
     */
    const REFUNDED_STATUS = 'refunded';

    /**
     * Completed partial refund after a successful payment
     */
    const PART_REFUNDED_STATUS = 'partially refunded';

    /**
     * Payment processing at Gate
     */
    const PROCESSING_STATUS = 'processing';

    /**
     * An error occurred while reviewing data for payment processing
     */
    const ERROR_STATUS = 'error';

    /**
     * Refund after a successful payment before closing of the business day
     */
    const REVERSED_STATUS = 'reversed';

    /**
     * Callback data as array
     *
     * @var array
     */
    private $data;

    /**
     * Signature Handler
     *
     * @var SignatureHandler
     */
    private $signatureHandler;

    /**
     * @param string|array $data RAW or already processed data from gate
     * @param SignatureHandler $signatureHandler
     * @throws ProcessException
     */
    public function __construct(string $data, SignatureHandler $signatureHandler)
    {
        $this->data = is_array($data) ? $data : $this->toArray($data);
        $this->signatureHandler = $signatureHandler;

        if (!$this->checkSignature()) {
            throw new ProcessException("Signature {$this->getSignature()} is invalid");
        }
    }

    /**
     * Returns already parsed gate data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get payment info
     *
     * @return mixed
     */
    public function getPayment()
    {
        return $this->getValueByName('payment');
    }

    /**
     * Get payment status
     *
     * @return string
     */
    public function getPaymentStatus(): string
    {
        return $this->getValueByName('status', $this->getPayment());
    }

    /**
     * Get payment ID
     *
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->getValueByName('id', $this->getPayment());
    }

    /**
     * Get signature
     *
     * @return string
     */
    public function getSignature(): string
    {
        return $this->getValueByName('signature');
    }

    /**
     * Cast raw data to array
     *
     * @param string $rawData
     *
     * @return array
     *
     * @throws ProcessException
     */
    public function toArray($rawData): array
    {
        $data = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ProcessException('Error on response decoding');
        }

        return $data;
    }

    /**
     * Get value by name
     *
     * @param string $name Param name
     * @param array $data Data to search in
     *
     * @return mixed
     */
    private function getValueByName($name, array $data = [])
    {
        if (!$data) {
            $data = $this->data;
        }

        if (!array_key_exists($name, $data)) {
            foreach ($data as $key => $value) {
                if (\is_array($value)) {
                    return $this->getValueByName($name, $value);
                }
            }
        }

        return $data[$name];
    }

    /**
     * checkSignature
     *
     * @return boolean
     */
    public function checkSignature(): bool
    {
        $data = $this->data;
        $signature = $this->getSignature();
        $this->removeParam('signature', $data);
        return $this->signatureHandler->check($data, $signature);
    }

    /**
     * Unset param at callback adata
     *
     * @param string $name param name
     * @param array $data tmp data
     */
    private function removeParam($name, array &$data)
    {
        if (isset($data[$name])) {
            unset($data[$name]);
        }

        foreach ($data as &$val) {
            if (\is_array($val)) {
                $this->removeParam($name, $val);
            }
        }
    }

    /**
     * Reads input data from gate
     * @return string
     */
    public static function readData(): string
    {
        return file_get_contents('php://input') ?: '{}';
    }
}
