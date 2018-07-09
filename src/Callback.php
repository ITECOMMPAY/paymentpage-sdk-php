<?php

namespace ecommpay;

/**
 * Callback
 */
class Callback
{
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
     *
     * @param string $data RAW data from gate
     * @param SignatureHandler $signatureHandler
     * @throws ProcessException
     */
    public function __construct($data, $signatureHandler)
    {
        $this->data = $this->toArray($data);
        $this->signatureHandler = $signatureHandler;
        if (!$this->checkSignature()) {
            throw new ProcessException("Signature {$this->getSignature()} is invalid");
        }
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
        return $this->signatureHandler->check($data['body'], $signature);
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
}
