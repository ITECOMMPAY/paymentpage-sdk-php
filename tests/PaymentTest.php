<?php

namespace ecommpay\tests;

use ecommpay\Payment;

class PaymentTest extends \PHPUnit\Framework\TestCase
{

    public function testGetParams()
    {
        $payment = (new Payment(100))->setPaymentId('test payment id');
        $expected = [
            'project_id' => '100',
            'payment_id' => 'test payment id',
            'interface_type' => json_encode(['id' => Payment::INTERFACE_TYPE]),
        ];
        self::assertEquals($expected, $payment->getParams());
    }

    public function testSetBestBefore()
    {
        $payment = new Payment(100);

        $payment
            ->setPaymentId('test payment id')
            ->setBestBefore(new \DateTime('2000-01-01 00:00:00 +0000'));
        self::assertEquals('2000-01-01T00:00:00+00:00', $payment->getParams()['best_before']);
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
