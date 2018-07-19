<?php

namespace ecommpay;

/**
 * Sender
 *
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com>
 * @license PHP Version 7+
 */
class Sender
{
    const GATE_API_URL = 'https://api.ecommpay.com/v2/';

    /**
     * action
     *
     * @var string
     */
    private $action;

    /**
     * request
     *
     * @var array
     */
    private $request;

    /**
     * __construct
     *
     * @param string $action
     * @param array $request
     */
    public function __construct(string $action, array $request)
    {
        $this->action = $action;
        $this->request = $request;
    }

    /**
     * Send request to gate
     *
     * @return mixed
     */
    public function send()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::GATE_API_URL . $this->action);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->request));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
