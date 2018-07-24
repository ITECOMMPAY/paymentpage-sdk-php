<?php

namespace ecommpay\Request\Validator;

use ecommpay\Request\Validator;
use ecommpay\Request;
use ecommpay\ProcessException;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    private $params = [
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
        'save' => true,
    ];

    public function testCheckAuth()
    {
        $validator = new Validator(Request::PAYMENT_CARD_AUTH, $this->params);
        $this->assertTrue($validator->check());
    }
    
    public function testCheckSale()
    {
        $validator = new Validator(Request::PAYMENT_CARD_SALE, $this->params);
        $this->assertTrue($validator->check());
    }
    
    public function testCheckRefund()
    {
        $validator = new Validator(Request::PAYMENT_CARD_REFUND, $this->params);
        $this->assertTrue($validator->check());
    }

    public function testCheckPaymentStatus()
    {
        $validator = new Validator(Request::PAYMENT_STATUS, $this->params);
        $this->assertTrue($validator->check());
    }

    public function testCheckCapture()
    {
        $validator = new Validator(Request::PAYMENT_CARD_CAPTURE, $this->params);
        $this->assertTrue($validator->check());
    }

    public function testCheckComplete()
    {
        $validator = new Validator(Request::PAYMENT_CARD_COMPLETE, $this->params);
        $this->assertTrue($validator->check());
    }

    public function testCheckCancel()
    {
        $validator = new Validator(Request::PAYMENT_CARD_CANCEL, $this->params);
        $this->assertTrue($validator->check());
    }

    public function testExceptions()
    {
        // data type check
        $params = $this->getParams();
        $params['project_id'] = 'test';
        $validator = new Validator(Request::PAYMENT_CARD_SALE, $params);

        try 
        {
            $validator->check();
        } catch (ProcessException $e) {
            $this->assertEquals('Field name: project_id have to be INTEGER type. Actual type: string', $e->getMessage());
        }

        $params = $this->getParams();
        $params['payment_id'] = 111;
        $validator = new Validator(Request::PAYMENT_CARD_SALE, $params);

        try 
        {
            $validator->check();
        } catch (ProcessException $e) {
            $this->assertEquals('Field name: payment_id have to be STRING type. Actual type: integer', $e->getMessage());
        }

        $params = $this->getParams();
        $params['project_id'] = -111;
        $validator = new Validator(Request::PAYMENT_CARD_SALE, $params);

        try 
        {
            $validator->check();
        } catch (ProcessException $e) {
            $this->assertEquals('Integer field name: project_id has negative or 0 value', $e->getMessage());
        }

        $params = $this->getParams();
        $params['save'] = 'string';
        $validator = new Validator(Request::PAYMENT_CARD_SALE, $params);

        try 
        {
            $validator->check();
        } catch (ProcessException $e) {
            $this->assertEquals('Field name: save have to be BOOL type. Actual type: string', $e->getMessage());
        }

        // required fields check
        $params = $this->getParams();
        unset($params['project_id']);
        $validator = new Validator(Request::PAYMENT_CARD_SALE, $params);

        try 
        {
            $validator->check();
        } catch (ProcessException $e) {
            $this->assertEquals('Required fields project_id not present in source request', $e->getMessage());
        }

        // max length check
        $params = $this->getParams();
        $params['pan'] = 'sadkh;shaf;askjdfhaj;skdfhjsakhfsajhfaksjfhaksj;hfkja;shfjksdhfkjshfjksafhk;sa';
        $validator = new Validator(Request::PAYMENT_CARD_SALE, $params);

        try 
        {
            $validator->check();
        } catch (ProcessException $e) {
            $this->assertEquals('Length of Param: pan with value sadkh;shaf;askjdfhaj;skdfhjsakhfsajhfaksjfhaksj;hfkja;shfjksdhfkjshfjksafhk;sa more than 32 symbols', $e->getMessage());
        }

        // regexp check
        $params = $this->getParams();
        $params['currency'] = 'AAAAA';
        $validator = new Validator(Request::PAYMENT_CARD_SALE, $params);

        try 
        {
            $validator->check();
        } catch (ProcessException $e) {
            $this->assertEquals('Param name currency with value AAAAA doesnt match regular expression ^[A-Z]{3}$', $e->getMessage());
        }

        $validator = new Validator('ddd', $params);

        try 
        {
            $validator->check();
        } catch (ProcessException $e) {
            $this->assertEquals('Action: ddd not supported yet', $e->getMessage());
        }
    }

    private function getParams()
    {
        return $this->params;
    }
}
