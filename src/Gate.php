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

    private $sender;

    /**
     * @param string $secret Secret key
     */
    public function __construct($secret)
    {
        $this->sender = new Sender();
        $this->signatureHandler = new SignatureHandler($secret);
        $this->paymentPageUrlBuilder = new PaymentPage($this->signatureHandler);
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
     * @param string $data RAW string data from Gate
     *
     * @return Callback
     *
     * @throws ProcessException
     */
    public function handleCallback($data): Callback
    {
        return new Callback($data, $this->signatureHandler);
    }

    /**
     * Send request to gate 
     * 
     * @param string $action Request action 
     * @param array $params Request params
     * @throws ProcessException
     * @return mixed
     */
    public function send(string $action, array $params)
    {
        if (!array_key_exists($action, Request::getPermittedActions())) {
            throw new ProcessException("Action: {$action} not supported yet");
        }

        $request = Request::get($action, $params);
        return $this->sender->send($action, $request);
    }
}
