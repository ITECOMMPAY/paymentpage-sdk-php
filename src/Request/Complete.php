<?php

namespace ecommpay;

use Request\Validator;

/**
 * Complete
 *
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com>
 * @license PHP Version 7+
 */
class Complete
    extends ArrayObject
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

        $request['pares'] = $params['pares'];
        $request['md'] = $params['md'];

        parent::__construct($request);
    }
}
