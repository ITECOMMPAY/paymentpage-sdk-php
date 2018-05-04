<?php

namespace System;

/**
 * ExceptionHandler 
 * 
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com> 
 */
class ExceptionHandler
{
    public function handle($exception)
    {
        var_export($exception); die();
    }
}
