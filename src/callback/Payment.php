<?php

namespace ecommpay\callback;

use ecommpay\enums\EcpPaymentStatus;
use ecommpay\support\DataContainer;

class Payment extends DataContainer
{
    public const DESCRIPTION = 'description';
    public const METHOD = 'method';
    public const AMOUNT = 'amount';
    public const STATUS = 'status';
    public const ID = 'id';

    public function getStatus(): ?string
    {
        return $this->getValue(self::STATUS);
    }

    public function isSuccess(): bool
    {
        return $this->getStatus() === EcpPaymentStatus::SUCCESS;
    }

    public function getId(): ?string
    {
        return $this->getValue(self::ID);
    }

    public function getDescription(): ?string
    {
        return $this->getValue(self::DESCRIPTION);
    }

    public function getMethod(): ?string
    {
        return $this->getValue(self::METHOD);
    }

    public function getAmount(): ?int
    {
        $amount = $this->getValue(self::AMOUNT);
        return $amount != null ? intval($amount) : null;
    }
}
