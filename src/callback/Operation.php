<?php

namespace ecommpay\callback;

use ecommpay\support\DataContainer;

class Operation extends DataContainer
{
    public const REQUEST_ID = 'request_id';
    public const STATUS = 'status';
    public const SUCCESS_STATUS = 'success';

    public function getStatus(): ?string
    {
        return $this->getDataValue(self::STATUS);
    }

    public function isSuccess(): bool
    {
        return $this->getStatus() === self::SUCCESS_STATUS;
    }

    public function getRequestId(): ?string
    {
        return $this->getDataValue(self::REQUEST_ID);
    }
}
