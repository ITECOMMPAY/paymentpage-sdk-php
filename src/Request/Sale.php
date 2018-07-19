<?php

namespace ecommpay;

use Request\Validator;

/**
 * Sale
 *
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com>
 * @license PHP Version 7+
 */
class Sale
    extends ArrayObject
{
    public function __construct(array $params)
    {
        $request = [];
        $general = array_keys(Validator::$generalInfo);
        $customer = array_keys(Validator::$customerInfo);
        $card = array_keys(Validator::$cardInfo);
        $payment = array_keys(Validator::$paymentInfo);

        foreach ($params as $name => $value) {
            if (in_array($name, $general)) {
                $request['general'][$name] = $value;
            } else if (in_array($name, $customer)) {
                $request['customer'][$name] = $value;
            } else if (in_array($name, $payment)) {
                $request['payment'][$name] = $value;
            } else if (in_array($name, $card)) {
                $request['card'][$name] = $value;
            }
        }

        parent::__construct($request);
    }
}
