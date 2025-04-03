<?php

namespace ecommpay\tests;

use ecommpay\callback\Callback;
use ecommpay\exception\ProcessException;
use ecommpay\SignatureHandler;
use PHPUnit\Framework\TestCase;

class CallbackFormatTest extends TestCase
{
    /**
     * @var array
     */
    private $cases;

    protected function setUp(): void
    {
        $this->cases = require __DIR__ . '/data/callbackFormats.php';
    }

    /**
     * @throws ProcessException
     */
    public function testFormats()
    {
        foreach ($this->cases as $index => $callbackData) {
            echo('Testing callback ' . ($index + 1) . ".\n");
            $callback = (new Callback($callbackData, new SignatureHandler('123')));
            $payment = $callback->getPayment();
            if (
                $payment
            ) {
                self::assertNotNull($payment->getId());
            }

            $operation = $callback->getOperation();
            if (
                $operation
            ) {
                self::assertNotEmpty($operation->getStatus());
            }
        }
    }
}
