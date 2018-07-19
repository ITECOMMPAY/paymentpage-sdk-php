<?php

namespace ecommpay\Request;

/**
 * PaymentStatus
 *
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com>
 * @license PHP Version 7+
 */
class PaymentStatus extends \ArrayObject
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

        foreach ($params as $name => $value) {
            if (in_array($name, $general)) {
                $request['general'][$name] = $value;
            }
        }

        if (array_key_exists('destination', $params)) {
            $request['destination'] = $params['destination'];
        }

        parent::__construct($request);
    }
}
