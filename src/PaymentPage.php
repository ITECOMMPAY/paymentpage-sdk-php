<?php

namespace ecommpay;

use Exception;

/**
 * Payment page URL Builder
 */
class PaymentPage
{
    const PATH_PAYMENT = 'payment';

    /**
     * Base URL for payment
     *
     * @var string
     */
    private $baseUrl = 'https://paymentpage.ecommpay.com/';

    /**
     * Signature Handler
     *
     * @var SignatureHandler $signatureHandler
     */
    private $signatureHandler;

    /**
     * Encryptor
     *
     * @var Encryptor $encryptor
     */
    private $encryptor;

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
     * @param Encryptor $encryptor
     * @return $this
     */
    public function setEncryptor(Encryptor $encryptor): self
    {
        $this->encryptor = $encryptor;

        return $this;
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
     * @throws Exception
     */
    public function getUrl(Payment $payment): string
    {
        $query = http_build_query($payment->getParams());
        $signature = urlencode($this->signatureHandler->sign($payment->getParams()));
        $pathWithQuery = self::PATH_PAYMENT . '?'. $query . '&signature=' . $signature;

        if ($this->encryptor) {
            $pathWithQuery = $payment->getProjectId() . '/' . $this->encryptor->safeEncrypt($pathWithQuery);
        }

        return $this->getNormalizedBaseURL() . $pathWithQuery;
    }

    /**
     * @return string
     */
    private function getNormalizedBaseURL(): string
    {
        $regexp = sprintf('/\/%s$/', self::PATH_PAYMENT);

        if (preg_match($regexp, $this->baseUrl)) {
            $this->baseUrl = preg_replace($regexp, '/', $this->baseUrl);
        }

        return $this->baseUrl;
    }
}
