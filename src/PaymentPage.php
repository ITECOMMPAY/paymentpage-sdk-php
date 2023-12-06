<?php

namespace ecommpay;

/**
 * Payment page URL Builder
 */
class PaymentPage
{
    const
        PAYMENT_URL_PATTERN = '%s/payment?%s&signature=%s',
        VALIDATOR_URL_PATTERN = '%s/params/check?%s';

    /**
     * Base URL for payment
     *
     * @var string
     */
    private $baseUrl = 'https://paymentpage.ecommpay.com';

    /**
     * Base URL for payment
     *
     * @var string
     */
    private $apiUrl = 'https://sdk.ecommpay.com';

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

        $this->setBaseUrl($baseUrl);
    }

    /**
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl(string $baseUrl): self
    {
        if ($baseUrl) {
            $this->baseUrl = $baseUrl;
        }

        return $this;
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
        return sprintf(
            self::PAYMENT_URL_PATTERN,
            $this->baseUrl,
            http_build_query($payment->getParams()),
            urlencode($this->signatureHandler->sign($payment->getParams()))
        );
    }

    /**
     * Return full URL for check payment parameters.
     *
     * @param Payment $payment
     * @return string
     */
    public function getValidationUrl(Payment $payment): string
    {
        return sprintf(
            self::VALIDATOR_URL_PATTERN,
            $this->apiUrl,
            http_build_query($payment->getParams())
        );
    }
}
