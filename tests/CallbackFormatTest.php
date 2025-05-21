<?php

namespace ecommpay\tests;

use ecommpay\Callback;
use ecommpay\exception\ProcessException;
use ecommpay\SignatureHandler;
use PHPUnit\Framework\TestCase;

class CallbackFormatTest extends TestCase
{
    /**
     * @var array
     */
    private $cases;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->cases = require __DIR__ . '/data/callbackFormats.php';
    }

    /**
     * @throws ProcessException
     */
    public function testFormats()
    {
        foreach ($this->cases as $callbackData) {
            $this->addToAssertionCount(1);
            $callback = (new Callback($callbackData, new SignatureHandler('123')));
            $payment = $callback->getPayment();
            if ($payment) {
                self::assertNotNull($payment->getId());
            }

            $operation = $callback->getOperation();
            if ($operation) {
                self::assertNotEmpty($operation->getStatus());
            }
        }
    }
}
