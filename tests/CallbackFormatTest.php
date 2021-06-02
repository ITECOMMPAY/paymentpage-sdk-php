<?php

namespace ecommpay\tests;

use ecommpay\Callback;
use ecommpay\SignatureHandler;

class CallbackFormatTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var array
     */
    private $cases;

    protected function setUp()
    {
        $this->cases = require __DIR__ . '/data/callbackFormats.php';
    }

    public function testFormats()
    {
        foreach ($this->cases['isset_payment'] as $callbackData) {
            $callback = (new Callback($callbackData, new SignatureHandler('123')));

            self::assertNotEmpty($callback->getPayment());
            self::assertNotEmpty($callback->getPaymentId());
            $callback->getPaymentStatus();
        }

        foreach ($this->cases['empty_payment'] as $callbackData) {
            $callback = (new Callback($callbackData, new SignatureHandler('123')));

            self::assertEmpty($callback->getPayment());
            self::assertEmpty($callback->getPaymentId());
            self::assertEmpty($callback->getPaymentStatus());
        }
    }
}
