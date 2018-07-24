<?php

namespace ecommpay\tests;

use ecommpay\Request;
use ecommpay\ProcessException;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    protected $params = [
        'project_id' => 999999999,
        'payment_id' => '666',
        'amount' => 15,
        'currency' => 'RUB',
        'ip_address' => '1.1.1.1',
        'pan' => '41111111111111',
        'year' => 2020,
        'month' => 12,
        'card_holder' => 'GENERAL AN',
        'cvv' => '777',
        'pares' => 'sada',
        'md' => '32423',
    ];

    public function testGetPermittedActions()
    {
        $actions = [
            Request::PAYMENT_STATUS            => Request::PAYMENT_STATUS,
            Request::PAYMENT_CARD_AUTH         => Request::PAYMENT_CARD_AUTH,
            Request::PAYMENT_CARD_CANCEL       => Request::PAYMENT_CARD_CANCEL,
            Request::PAYMENT_CARD_CAPTURE      => Request::PAYMENT_CARD_CAPTURE,
            Request::PAYMENT_CARD_COMPLETE     => Request::PAYMENT_CARD_COMPLETE,
            Request::PAYMENT_CARD_REFUND       => Request::PAYMENT_CARD_REFUND,
            Request::PAYMENT_CARD_SALE         => Request::PAYMENT_CARD_SALE,
        ];

        $this->assertEquals($actions, Request::getPermittedActions());
    }

    public function testGetSale()
    {
        $this->assertEquals('ecommpay\Request\Sale', get_class(Request::get(Request::PAYMENT_CARD_SALE, $this->params)));
    }

    public function testGetAuth()
    {
        $this->assertEquals('ecommpay\Request\Auth', get_class(Request::get(Request::PAYMENT_CARD_AUTH, $this->params)));
    }

    public function testGetRefund()
    {
        $this->assertEquals('ecommpay\Request\Refund', get_class(Request::get(Request::PAYMENT_CARD_REFUND, $this->params)));
    }

    public function testGetPaymentStatus()
    {
        $this->assertEquals('ecommpay\Request\PaymentStatus', get_class(Request::get(Request::PAYMENT_STATUS, $this->params)));
    }

    public function testGetCancel()
    {
        $this->assertEquals('ecommpay\Request\Cancel', get_class(Request::get(Request::PAYMENT_CARD_CANCEL, $this->params)));
    }

    public function testGetCapture()
    {
        $this->assertEquals('ecommpay\Request\Capture', get_class(Request::get(Request::PAYMENT_CARD_CAPTURE, $this->params)));
    }

    public function testGetComplete()
    {
        $this->assertEquals('ecommpay\Request\Complete', get_class(Request::get(Request::PAYMENT_CARD_COMPLETE, $this->params)));
    }

    public function testUnknownAction()
    {
        try
        {
            Request::get('ololo', $this->params);
        } catch (ProcessException $e) {
            $this->assertEquals('Action: ololo not supported yet', $e->getMessage());
        }
    }
}
