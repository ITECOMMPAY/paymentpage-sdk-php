<?php

namespace ecommpay\callback;

use ecommpay\exception\ProcessException;
use ecommpay\exception\SdkException;
use ecommpay\SignatureHandler;
use ecommpay\support\DataContainer;

use function is_array;

/**
 * Callback
 */
class Callback extends DataContainer
{
    public const PAYMENT_FIELD = 'payment';
    public const OPERATION_FIELD = 'operation';

    private SignatureHandler $signatureHandler;

    private Payment $payment;

    private Operation $operation;

    /**
     * @param string|array $data RAW or already processed data from gate
     * @param SignatureHandler $signatureHandler
     * @throws ProcessException
     */
    public function __construct($data, SignatureHandler $signatureHandler)
    {
        parent::__construct($data);

        $this->signatureHandler = $signatureHandler;

        if (!$this->checkSignature()) {
            throw new ProcessException(
                sprintf('Signature %s is invalid', $this->getSignature()),
                SdkException::INVALID_SIGNATURE
            );
        }

        $payment = $this->getDataValue(self::PAYMENT_FIELD);
        if (!empty($payment)) {
            $this->payment = new Payment($payment);
        }

        $operation = $this->getDataValue(self::OPERATION_FIELD);
        if (!empty($operation)) {
            $this->operation = new Operation($operation);
        }
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function getOperation(): Operation
    {
        return $this->operation;
    }

    public function isSuccess(): bool
    {
        return $this->payment->isSuccess();
    }

    /**
     * Get signature
     *
     * @return string
     * @throws ProcessException
     */
    public function getSignature(): string
    {
        $signature = $this->getDataValue('signature')
            ?? $this->getDataValue('general.signature');

        if (!$signature) {
            throw new ProcessException('Undefined signature', SdkException::UNDEFINED_SIGNATURE);
        }

        return $signature;
    }

    /**
     * checkSignature
     *
     * @return bool
     * @throws ProcessException
     */
    public function checkSignature(): bool
    {
        $data = $this->getData();
        $signature = $this->getSignature();

        $this->removeParam('signature', $data);
        $this->setData($data);

        return $this->signatureHandler->check($data, $signature);
    }

    /**
     * Unset param at callback data
     *
     * @param string $name param name
     * @param array $data tmp data (passed by reference)
     */
    private function removeParam(string $name, array &$data): void
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
