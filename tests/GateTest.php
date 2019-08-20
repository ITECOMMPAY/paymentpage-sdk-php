<?php

namespace ecommpay\tests;

use ecommpay\Callback;
use ecommpay\Gate;
use ecommpay\Payment;

class GateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    private $testUrl = 'http://test-url.test/test';

    /**
     * @var Gate
     */
    private $gate;

    protected function setUp()
    {
        $this->gate = new Gate('secret', $this->testUrl);
    }

    public function testGetPurchasePaymentPageUrl()
    {
        $paymentUrl = $this->gate->getPurchasePaymentPageUrl(new Payment(100, 'test payment id'));

        self::assertNotEmpty($paymentUrl);
        self::assertTrue(strpos($paymentUrl, $this->testUrl) !== false);
    }

    public function testSetPaymentBaseUrl()
    {
        $someTestUrl = 'http://test-url.test/test';

        self::assertEquals(Gate::class, get_class($this->gate->setPaymentBaseUrl($someTestUrl)));

        $paymentUrl = $this->gate->getPurchasePaymentPageUrl(new Payment(100, 'test payment id'));

        self::assertTrue(strpos($paymentUrl, $someTestUrl) !== false);
    }

    public function testHandleCallback()
    {
        $callback = $this->gate->handleCallback(require __DIR__ . '/data/callback.php');

        self::assertInstanceOf(Callback::class, $callback);
    }
}
