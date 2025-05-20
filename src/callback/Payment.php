<?php

namespace ecommpay\callback;

use ecommpay\enums\EcpPaymentStatus;
use ecommpay\support\DataContainer;

class Payment extends DataContainer
{
    const DESCRIPTION = 'description';
    const METHOD = 'method';
    const AMOUNT = 'amount';
    const STATUS = 'status';
    const ID = 'id';

    /**
     * @return string|null
     */
    public function getStatus()
    {
        return $this->getValue(self::STATUS);
    }

    public function isSuccess(): bool
    {
        return $this->getStatus() === EcpPaymentStatus::SUCCESS;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->getValue(self::ID);
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getValue(self::DESCRIPTION);
    }

    /**
     * @return string|null
     */
    public function getMethod()
    {
        return $this->getValue(self::METHOD);
    }

    /**
     * @return int|null
     */
    public function getAmount()
    {
        $amount = $this->getValue(self::AMOUNT);
        return $amount !== null ? (int) $amount : null;
    }
}
