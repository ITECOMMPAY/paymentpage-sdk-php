<?php

namespace ecommpay\tests;

use ecommpay\Callback;
use ecommpay\exception\ProcessException;
use ecommpay\exception\ValidationException;
use ecommpay\Gate;
use ecommpay\Payment;
use PHPUnit\Framework\TestCase;

class GateTest extends TestCase
{
    /** @var string */
    private $testUrl = 'http://test-url.test/test';

    /** @var Gate */
    private $gate;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->gate = new Gate('secret', $this->testUrl);
    }

    /**
     * @throws ValidationException
     */
    public function testGetPurchasePaymentPageUrl()
    {
        $payment = (new Payment(100))->setPaymentId('test payment id');
        $paymentUrl = $this->gate->getPurchasePaymentPageUrl($payment);

        self::assertNotEmpty($paymentUrl);
        self::assertStringStartsWith($this->testUrl, $paymentUrl);
    }

    /**
     * @throws ValidationException
     */
    public function testSetPaymentBaseUrl()
    {
        $someTestUrl = 'http://some-test-url.test/test';

        self::assertEquals(Gate::class, get_class($this->gate->setPaymentBaseUrl($someTestUrl)));

        $paymentUrl = $this->gate->getPurchasePaymentPageUrl(new Payment(100));

        self::assertStringStartsWith($someTestUrl, $paymentUrl);
    }

    /**
     * @throws ProcessException
     */
    public function testHandleCallback()
    {
        $callback = $this->gate->handleCallback(require __DIR__ . '/data/callback.php');

        self::assertInstanceOf(Callback::class, $callback);
    }
}
