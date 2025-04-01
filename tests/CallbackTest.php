<?php

namespace ecommpay\tests;

use ecommpay\exception\ProcessException;
use ecommpay\Gate;
use PHPUnit\Framework\TestCase;

class CallbackTest extends TestCase
{
    /**
     * @var Callback
     */
    private $callback;

    protected function setUp(): void
    {
        $gate = new Gate('secret');
        $this->callback =
            $gate
                ->handleCallback(require __DIR__ . '/data/callback.php');
    }

    public function testGetPaymentId()
    {
        self::assertEquals('000049', $this->callback->getPayment()->getId());
    }

    public function testGetPayment()
    {
        self::assertArrayHasKey('id', $this->callback->getPayment());
        self::assertArrayHasKey('status', $this->callback->getPayment());
    }

    /**
     * @throws ProcessException
     */
    public function testGetSignature()
    {
        self::assertNotEmpty($this->callback->getSignature());
    }

    public function testGetPaymentStatus()
    {
        self::assertEquals('success', $this->callback->getPayment()->getStatus());
    }
}
