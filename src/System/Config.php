<?php

namespace System;

use Gate\ProcessException;

/**
 * Config 
 * 
 * @package 
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com> 
 */
class Config
{
    /**
     * Get Merchant's config from file 
     * 
     * @param string $path Config file route
     * @return array
     */
    public static function fromFile($path = CONFIG_PATH)
    {
        if (!file_exists($path)) {
            throw new ProcessException("Config file with path {$path} doesnt exist");
        }
        return include $path;
    }
}

