<?php

namespace ecommpay;

use Exception;

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
     * Gate constructor.
     *
     * @param string $secret Secret key
     * @param string $baseUrl Base URL for concatenate with payment params
     * @param string $encryptSecret Secret key for encode URL path and params
     */
    public function __construct(string $secret, string $baseUrl = '', string $encryptSecret = '')
    {
        $this->signatureHandler = new SignatureHandler($secret);
        $this->paymentPageUrlBuilder = new PaymentPage($this->signatureHandler, $baseUrl);

        if ($encryptSecret) {
            $this->paymentPageUrlBuilder->setEncryptor(new Encryptor($encryptSecret));
        }
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
     * @throws Exception
     */
    public function getPurchasePaymentPageUrl(Payment $payment): string
    {
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
}
