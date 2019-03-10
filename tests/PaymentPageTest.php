<?php

namespace ecommpay\tests;

use ecommpay\PaymentPage;
use ecommpay\SignatureHandler;
use ecommpay\Payment;

class PaymentPageTest extends \PHPUnit\Framework\TestCase
{

    public function testGetUrl()
    {
        $handler = new SignatureHandler('secret');
        $paymentPage = new PaymentPage($handler);
        $payment = new Payment(100, 'test payment id');
        $payment->setPaymentDescription('B&W');
        $url = $paymentPage->getUrl($payment);

        self::assertEquals(
            'https://paymentpage.ecommpay.com/payment?project_id=100&payment_id=test+payment+id&payment_description=' .
            'B%26W&signature=CbsLiP9y7LO9JIfo7IJlq0jRF%2F5oLGBVkCYP59VB4fdtUAyqj5a5A0RwBhUUbu8r%2Bcim9J3YL3x4lbr0wjM' .
            'mKg%3D%3D',
            $url
        );
    }
}
