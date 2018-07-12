<?php

namespace ecommpay;

class Sale
    extends ArrayObject
{
    public function __construct(array $params)
    {
        $request = [];


    
        parent::__construct($params);
    }
}
