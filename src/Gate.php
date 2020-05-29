<?php

namespace ecommpay;

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
     */
    public function __construct($secret, string $baseUrl = '')
    {
        $this->signatureHandler = new SignatureHandler($secret);
        $this->paymentPageUrlBuilder = new PaymentPage($this->signatureHandler, $baseUrl);
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
     */
    public function getPurchasePaymentPageUrl(Payment $payment): string
    {
        return $this->paymentPageUrlBuilder->getUrl($payment);
    }

    /**
     * Callback handler
     *
     * @param string|array $data
     *
     * @return Callback
     *
     * @throws ProcessException
     */
    public function handleCallback($data): Callback
    {
        return new Callback($data, $this->signatureHandler);
    }
}
