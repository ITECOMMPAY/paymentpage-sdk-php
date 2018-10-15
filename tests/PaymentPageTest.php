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
        $payment = new Payment(100);
        $payment->setPaymentDescription('B&W');
        $url = $paymentPage->getUrl($payment);

        self::assertEquals(
            'https://paymentpage.ecommpay.com/payment?project_id=100&payment_description=B%2526W' .
            '&signature=X9rP65p71v5vteLWHBroNr5NE1GrqBu%2FjyFKk7BhZVgtPIFiO3iquKIAPtKkuSD7htuWiLp8DRyfL4H9vT5d3A%3D%3D',
            $url
        );
    }
}
