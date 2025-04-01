<?php

namespace ecommpay\callback;

use ecommpay\enums\EcpPaymentStatus;
use ecommpay\support\DataContainer;

class Payment extends DataContainer
{
    const DESCRIPTION = 'description';
    const METHOD = 'method';
    const STATUS = 'status';
    const ID = 'id';

    public function getStatus()
    {
        return $this->getData()[self::STATUS];
    }

    public function isSuccess(): bool
    {
        return $this->getStatus() === EcpPaymentStatus::SUCCESS;
    }

    public function getId()
    {
        return $this->getData()[self::ID];
    }
}
