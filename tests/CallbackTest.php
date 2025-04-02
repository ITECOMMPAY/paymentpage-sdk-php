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

    /**
     * @throws ProcessException
     */
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
        self::assertNotEmpty($this->callback->getPayment()->getId());
        self::assertNotEmpty($this->callback->getPayment()->getStatus());
    }

    public function testGetPaymentStatus()
    {
        self::assertEquals('success', $this->callback->getPayment()->getStatus());
    }
}
