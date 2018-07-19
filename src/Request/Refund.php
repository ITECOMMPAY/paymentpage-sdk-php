<?php

namespace ecommpay\Request;

/**
 * Refund
 *
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com>
 * @license PHP Version 7+
 */
class Refund extends \ArrayObject
{

    /**
     * __construct
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $request = [];
        $general = array_keys(Validator::$generalInfo);
        $payment = array_keys(Validator::$paymentInfo);

        foreach ($params as $name => $value) {
            if (in_array($name, $general)) {
                $request['general'][$name] = $value;
            } elseif (in_array($name, $card)) {
                $request['card'][$name] = $value;
            }
        }

        parent::__construct($request);
    }
}
