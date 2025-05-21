<?php

namespace ecommpay\callback;

use ecommpay\support\DataContainer;

class Operation extends DataContainer
{
    const REQUEST_ID = 'request_id';
    const STATUS = 'status';
    const SUCCESS_STATUS = 'success';

    /**
     * @return string|null
     */
    public function getStatus()
    {
        return $this->getValue(self::STATUS);
    }

    public function isSuccess(): bool
    {
        return $this->getStatus() === self::SUCCESS_STATUS;
    }

    /**
     * @return string|null
     */
    public function getRequestId()
    {
        return $this->getValue(self::REQUEST_ID);
    }
}
