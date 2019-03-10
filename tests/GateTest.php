<?php

namespace ecommpay\tests;

use ecommpay\Callback;
use ecommpay\Gate;
use ecommpay\Payment;

class GateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Gate
     */
    private $gate;

    protected function setUp()
    {
        $this->gate = new Gate('secret');
    }

    public function testGetPurchasePaymentPageUrl()
    {
        self::assertNotEmpty($this->gate->getPurchasePaymentPageUrl(new Payment(100, 'test payment id')));
    }

    public function testHandleCallback()
    {
        $callback = $this->gate->handleCallback(require __DIR__ . '/data/callback.php');

        self::assertInstanceOf(Callback::class, $callback);
    }
}
