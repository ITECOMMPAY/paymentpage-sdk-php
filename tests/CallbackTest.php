<?php

namespace ecommpay\tests;

use ecommpay\Callback;
use ecommpay\Gate;

class CallbackTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Callback
     */
    private $callback;

    protected function setUp()
    {
        $this->callback = (new Gate('secret'))
            ->handleCallback(require __DIR__ . '/data/callback.php');
    }

    public function testGetPaymentId()
    {
        self::assertEquals('000049', $this->callback->getPaymentId());
    }

    public function testGetPayment()
    {
        self::assertArrayHasKey('id', $this->callback->getPayment());
        self::assertArrayHasKey('status', $this->callback->getPayment());
    }

    public function testGetSignature()
    {
        self::assertNotEmpty($this->callback->getSignature());
    }

    public function testGetPaymentStatus()
    {
        self::assertEquals('success', $this->callback->getPaymentStatus());
    }
}
