<?php

namespace ecommpay\callback;

use ecommpay\support\DataContainer;

class Operation extends DataContainer
{
    const REQUEST_ID = 'request_id';
    const STATUS = 'status';
    const SUCCESS_STATUS = 'success';

    public function getStatus(): ?string
    {
        return $this->getData()[self::STATUS];
    }

    public function isSuccess(): bool
    {
        return $this->getStatus() === self::SUCCESS_STATUS;
    }

    public function getRequestId(): ?string
    {
        return $this->getData()[self::REQUEST_ID];
    }
}
