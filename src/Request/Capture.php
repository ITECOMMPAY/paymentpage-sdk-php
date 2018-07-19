<?php

namespace ecommpay\Request;

/**
 * Capture
 *
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com>
 * @license PHP Version 7+
 */
class Capture extends \ArrayObject
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

        parent::__construct($request);
    }
}
