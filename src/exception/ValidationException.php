<?php

namespace ecommpay\exception;

use Exception;
use Throwable;

/**
 * Process exception in library
 */
class ValidationException extends Exception implements SdkException
{
    const MESSAGE = 'One or more payment params is not valid';

    /**
     * @var array
     */
    private $errors;

    public function __construct(array $errors, Throwable $previous = null)
    {
        $this->errors = $errors;
        parent::__construct(self::MESSAGE, self::VALIDATION_ERROR, $previous);
    }

    final public function getErrors(): array
    {
        return $this->errors;
    }
}
