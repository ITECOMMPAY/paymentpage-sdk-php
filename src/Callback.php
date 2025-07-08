<?php

namespace ecommpay;

use ecommpay\exception\ProcessException;

use ecommpay\exception\SdkException;

use function is_array;

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
    const CANCELED_STATUS = 'canceled';

    /**
     * @deprecated use Callback::CANCELED_STATUS instead
     * @see Callback::CANCELED_STATUS
     *
     * Holding of funds (produced on authorization request) is cancelled
     */
    const CANCELLED_STATUS = self::CANCELED_STATUS;

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
     * Completed partial refund after a successful payment before closing of the business day
     */
    const PART_REVERSED_STATUS = 'partially reversed';

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
    public function __construct($data, SignatureHandler $signatureHandler)
    {
        $this->data = is_array($data) ? $data : $this->toArray($data);
        $this->signatureHandler = $signatureHandler;

        if (!$this->checkSignature()) {
            throw new ProcessException(
                sprintf('Signature %s is invalid', $this->getSignature()),
                SdkException::INVALID_SIGNATURE
            );
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
     * @return ?array
     */
    public function getPayment()
    {
        return $this->getValue('payment');
    }

    /**
     * Get payment status
     *
     * @return ?string
     */
    public function getPaymentStatus()
    {
        return $this->getValue('payment.status');
    }

    /**
     * Get payment ID
     *
     * @return ?string
     */
    public function getPaymentId()
    {
        return $this->getValue('payment.id');
    }

    /**
     * Get signature
     *
     * @return string
     * @throws ProcessException
     */
    public function getSignature(): string
    {
        $signature = $this->getValue('signature')
            ?? $this->getValue('general.signature');

        if (!$signature) {
            throw new ProcessException('Undefined signature', SdkException::UNDEFINED_SIGNATURE);
        }

        return $signature;
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
    public function toArray(string $rawData): array
    {
        $data = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ProcessException('Error on response decoding', SdkException::DECODING_ERROR);
        }

        return $data;
    }

    /**
     * Get value by name path
     *
     * @param string $namePath
     *
     * @return mixed
     */
    public function getValue(string $namePath)
    {
        $keys = explode('.', $namePath);
        $callbackData = $this->data;

        foreach ($keys as $key) {
            $value = $callbackData[$key] ?? null;

            if (is_null($value)) {
                return null;
            }

            $callbackData = $value;
        }

        return $callbackData;
    }

    /**
     * checkSignature
     *
     * @return boolean
     * @throws ProcessException
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
    private function removeParam(string $name, array &$data)
    {
        if (isset($data[$name])) {
            unset($data[$name]);
        }

        foreach ($data as &$val) {
            if (is_array($val)) {
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
