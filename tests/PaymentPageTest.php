<?php

namespace ecommpay\tests;

use ecommpay\Payment;
use ecommpay\PaymentPage;
use ecommpay\SignatureHandler;

class PaymentPageTest extends \PHPUnit\Framework\TestCase
{

    public function testGetUrl()
    {
        $handler = new SignatureHandler('secret');
        $paymentPage = new PaymentPage($handler);

        $payment = new Payment(100);
        $payment->setPaymentDescription('B&W purchase');
        $url = $paymentPage->getUrl($payment);

        $signature = urlencode($handler->sign($payment->getParams()));
        self::assertEquals(
            'https://paymentpage.ecommpay.com/payment?project_id=100&payment_description=B%26W+purchase' .
            '&signature=' . $signature,
            $url
        );
    }
}
