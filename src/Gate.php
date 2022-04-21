<?php

namespace ecommpay;

use ecommpay\exception\ProcessException;
use ecommpay\exception\ValidationException;

/**
 * Gate
 */
class Gate
{
    const CURRENCY_RUB = 'RUB';
    const CURRENCY_USD = 'USD';
    const CURRENCY_EUR = 'EUR';

    /**
     * Builder for Payment page
     *
     * @var PaymentPage $paymentPageUrlBuilder
     */
    private $paymentPageUrlBuilder;

    /**
     * Signature Handler (check, sign)
     *
     * @var SignatureHandler $signatureHandler
     */
    private $signatureHandler;

    /**
     * Flag validate payment params before generate PaymentPage URL.
     *
     * @var bool
     */
    private $validateParams = false;

    /**
     * Gate constructor.
     *
     * @param string $secret Secret key
     * @param string $baseUrl Base URL for concatenate with payment params
     */
    public function __construct(string $secret, string $baseUrl = '')
    {
        $this->signatureHandler = new SignatureHandler($secret);
        $this->paymentPageUrlBuilder = new PaymentPage($this->signatureHandler, $baseUrl);
    }

    /**
     * Enable or disable validation payment params before generate PaymentPage URL.
     * @param bool $flag
     * @return void
     */
    public function setValidationParams(bool $flag)
    {
        $this->validateParams = $flag;
    }

    /**
     * @param string $paymentBaseUrl
     * @return Gate
     */
    public function setPaymentBaseUrl(string $paymentBaseUrl = ''): self
    {
        $this->paymentPageUrlBuilder->setBaseUrl($paymentBaseUrl);

        return $this;
    }

    /**
     * Get URL for purchase payment page
     *
     * @param Payment $payment Payment object
     *
     * @return string
     * @throws ValidationException
     */
    public function getPurchasePaymentPageUrl(Payment $payment): string
    {
        if ($this->validateParams) {
            $this->validateParams($payment);
        }

        return $this->paymentPageUrlBuilder->getUrl($payment);
    }

    /**
     * Callback handler
     *
     * @param string $data RAW string data from Gate
     *
     * @return Callback
     *
     * @throws ProcessException
     */
    public function handleCallback(string $data): Callback
    {
        return new Callback($data, $this->signatureHandler);
    }


    /**
     * @param Payment $payment
     * @return void
     * @throws ValidationException
     */
    private function validateParams(Payment $payment)
    {
        $requestUri = $this->paymentPageUrlBuilder->getValidationUrl($payment);
        $stream = fopen($requestUri, 'r');
        $errors = [];
        $status = 0;

        // Reverse required!!!
        $headers = array_reverse(stream_get_meta_data($stream)['wrapper_data']);

        foreach ($headers as $header) {
            if (preg_match('/^HTTP\/\d.\d (\d+) /', $header, $match)) {
                $status = (int) $match[1];
                break;
            }
        }

        if ($status !== 200) {
            $data = json_decode(stream_get_contents($stream));
            $errors = $data['errors'];
        }

        fclose($stream);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}
