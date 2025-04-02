# Ecommpay PHP SDK

[![Build Status](https://travis-ci.org/ITECOMMPAY/paymentpage-sdk-php.svg?branch=master)](https://travis-ci.org/ITECOMMPAY/paymentpage-sdk-php)
[![Test Coverage](https://api.codeclimate.com/v1/badges/13f0385331642461cba7/test_coverage)](https://codeclimate.com/github/ITECOMMPAY/paymentpage_sdk/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/13f0385331642461cba7/maintainability)](https://codeclimate.com/github/ITECOMMPAY/paymentpage_sdk/maintainability)

This is a set of libraries in the PHP language to ease integration of your service
with the Ecommpay Payment Page.

Please note that for correct SDK operating you must have at least PHP 7.0.

## Payment flow

![Payment flow](flow.png)

## Installation

Install with composer

```bash
composer require ecommpay/paymentpage-sdk
```

### Get URL for payment

```php
$gate = new ecommpay\Gate('secret');
$payment = new ecommpay\Payment('11', 'some payment id');
$payment->setPaymentAmount(1000)->setPaymentCurrency('RUB');
$url = $gate->getPurchasePaymentPageUrl($payment);
``` 

`$url` here is the signed URL.

If you want to use another domain for URL you can change it with optional `Gate` constructor parameter:

```php
new ecommpay\Gate('secret', 'https://mydomain.com/payment');
```

or change it with method

```php
$gate->setPaymentBaseUrl('https://mydomain.com/payment');
```

### Handle callback from Ecommpay

You'll need to autoload this code in order to handle notifications:

```php
use ecommpay\enums\EcpPaymentStatus;$gate = new ecommpay\Gate('secret');
$callback = $gate->handleCallback($data);

// For example:
$payment_id = $callback->getPayment()->getId();
$payment_status = $callback->getPayment()->getStatus();
$is_success = $callback->isSuccess();

// Different approaches (more examples):
$callback->getPayment()->getValue('status') === EcpPaymentStatus::AWAITING_CUSTOMER;
$callback->getValue('payment.status') === EcpPaymentStatus::PARTIALLY_REFUNDED;
$callback->getData()['payment']['status'] === EcpPaymentStatus::AWAITING_3DS_RESULT;
```

`$data` is the JSON data received from payment system;

`$callback` is the Callback object describing properties received from payment system;
