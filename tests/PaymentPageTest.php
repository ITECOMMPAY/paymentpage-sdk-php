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
        $url = $paymentPage->getUrl($payment);

        self::assertEquals(
            'https://paymentpage.ecommpay.com/payment?project_id=100&signature=kP9TBXZQj+QqU' .
            '/TVcaJHAmgtXqCHmM5wexUCp5eVTaTeNmVZ0axXMspVk9jd74Fu1n6ZL2Wv3HB87Wn/fXJnEA==',
            $url
        );
    }
}
