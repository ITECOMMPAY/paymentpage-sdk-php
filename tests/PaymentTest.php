<?php

namespace ecommpay\tests;

use ecommpay\Payment;

class PaymentTest extends \PHPUnit\Framework\TestCase
{

    public function testGetParams()
    {
        $payment = new Payment(100);
        $expected = [
            'project_id' => '100',
        ];
        self::assertEquals($expected, $payment->getParams());
    }

    public function testSetCashierPredefinedAmounts()
    {
        $payment = new Payment(100);
        $payment->setCashierPredefinedAmounts([10, 20]);
        self::assertEquals('10,20', $payment->getParams()['cashier_predefined_amounts']);
    }

    public function testSetBestBefore()
    {
        $payment = new Payment(100);
        $payment->setBestBefore(new \DateTime('2000-01-01'));
        self::assertEquals('Sat, 01 Jan 2000 00:00:00 +0000', $payment->getParams()['best_before']);
    }

    public function testMagicMethods()
    {
        $payment = new Payment(100);
        $payment->setAccountToken('token')->setCardOperationType('type');

        self::assertEquals('token', $payment->getParams()['account_token']);
        self::assertEquals('type', $payment->getParams()['card_operation_type']);
        self::expectException(\BadMethodCallException::class);
        $payment->nonExistantMethod();
    }
}
