<?php

namespace ecommpay;

/**
 * Payment page URL Builder
 */
class PaymentPage
{
    /**
     * Base URL for payment
     *
     * @var string
     */
    private $baseUrl = 'https://paymentpage.ecommpay.com/payment';

    /**
     * Signature Handler
     *
     * @var SignatureHandler $signatureHandler
     */
    private $signatureHandler;

    /**
     * @param SignatureHandler $signatureHandler
     * @param string $baseUrl
     */
    public function __construct(SignatureHandler $signatureHandler, string $baseUrl = '')
    {
        $this->signatureHandler = $signatureHandler;

        if ($baseUrl) {
            $this->baseUrl = $baseUrl;
        }
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Get full URL for payment
     *
     * @param Payment $payment
     *
     * @return string
     */
    public function getUrl(Payment $payment): string
    {
        $params = $payment->getParams();
        $params['signature'] = $this->signatureHandler->sign($params);

        return $this->baseUrl . '?' . http_build_query($params);
    }
}
