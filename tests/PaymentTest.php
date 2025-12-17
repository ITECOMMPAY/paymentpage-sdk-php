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
        $payment->setAccountToken('token');

        self::assertEquals('token', $payment->getParams()['account_token']);
        $this->expectException(\BadMethodCallException::class);
        $payment->nonExistantMethod();
    }

    public function testCardOperationType()
    {
        $payment = new Payment(100, 'test payment id');
        $payment->setCardOperationType('sale');

        $urlParams = $payment->getParams();

        self::assertArrayHasKey('operation_type', $urlParams);
        self::assertEquals('sale', $urlParams['operation_type']);

        self::assertArrayNotHasKey('card_operation_type', $urlParams);
    }

    public function testOperationType()
    {
        $payment = new Payment(100, 'test payment id');
        $payment->setOperationType('auth');

        $urlParams = $payment->getParams();

        self::assertArrayHasKey('operation_type', $urlParams);
        self::assertEquals('auth', $urlParams['operation_type']);
    }

    public function testBookingInfo()
    {
        $expectedBase64 = 'eyJmaXJzdF9uYW1lIjoiSm9lIiwic3VybmFtZSI6IkRvZSIsInN0YXJ0X2RhdGUiOiIyMS0wMS0yMDI1IiwiZW5kX2RhdGUiOiIyMi0wMS0yMDI1In0=';
        $payment = new Payment(100, 'test payment id');

        $payment->setBookingInfo([
            'first_name' => 'Joe',
            'surname' => 'Doe',
            'start_date' => '21-01-2025',
            'end_date' => '22-01-2025',
        ]);

        self::assertEquals($expectedBase64, $payment->getParams()['booking_info'] ?? '');
    }
}
