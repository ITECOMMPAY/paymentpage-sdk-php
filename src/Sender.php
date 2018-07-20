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
     * send
     *
     * @param mixed $action Request action
     * @param mixed $request Request params
     * @return mixed
     */
    public static function send(string $action, array $request)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::GATE_API_URL . $action);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
