<?php

namespace ecommpay\tests;

use ecommpay\Payment;

class PaymentTest extends \PHPUnit\Framework\TestCase
{

    public function testGetParams()
    {
        $payment = new Payment(100, 'test payment id');
        $expected = [
            'project_id' => '100',
            'payment_id' => 'test payment id',
        ];
        self::assertEquals($expected, $payment->getParams());
    }

    public function testSetCashierPredefinedAmounts()
    {
        $payment = new Payment(100, 'test payment id');
        $payment->setCashierPredefinedAmounts([10, 20]);
        self::assertEquals('10,20', $payment->getParams()['cashier_predefined_amounts']);
    }

    public function testSetBestBefore()
    {
        $payment = new Payment(100, 'test payment id');
        $payment->setBestBefore(new \DateTime('2000-01-01 00:00:00 +0000'));
        self::assertEquals('Sat, 01 Jan 2000 00:00:00 +0000', $payment->getParams()['best_before']);
    }

    public function testMagicMethods()
    {
        $payment = new Payment(100, 'test payment id');
        $payment->setAccountToken('token')->setCardOperationType('type');

        self::assertEquals('token', $payment->getParams()['account_token']);
        self::assertEquals('type', $payment->getParams()['card_operation_type']);
        self::expectException(\BadMethodCallException::class);
        $payment->nonExistantMethod();
    }
}
