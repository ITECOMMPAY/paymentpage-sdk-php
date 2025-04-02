<?php

namespace ecommpay\callback;

use ecommpay\enums\EcpPaymentStatus;
use ecommpay\support\DataContainer;

class Payment extends DataContainer
{
    public const DESCRIPTION = 'description';
    public const METHOD = 'method';
    public const STATUS = 'status';
    public const ID = 'id';

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
