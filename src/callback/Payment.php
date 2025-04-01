<?php

namespace ecommpay\callback;

use ecommpay\support\DataContainer;

class Payment extends DataContainer
{
    const DESCRIPTION = 'description';
    const METHOD = 'method';
    const STATUS = 'status';
    const ID = 'id';

    const SUCCESS_STATUS = 'success';
    const DECLINE_STATUS = 'decline';

    public function getStatus()
    {
        return $this->getData()[self::STATUS];
    }

    public function getId()
    {
        return $this->getData()[self::ID];
    }
}
