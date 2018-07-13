<?php

namespace ecommpay;

class Validator
{
    private $action;
    private $params;

    public function __construct(string $action, array $params)
    {
        $this->action = $action;
        $this->params = $params;
    }

    public function check()
    {
        switch ($action)
        {
        case self::PAYMENT_CARD_SALE:
        case self::PAYMENT_CARD_AUTH:
            $this->checkGeneralInfo();
            $this->checkCardInfo();
            $this->checkCustomerInfo();
            $this->checkPaymentInfo();
            break;
        case self::PAYMENT_CARD_REFUND:
            $this->checkGeneralInfo();
            $this->checkPaymentInfo();
            break;
        case self::PAYMENT_CARD_CAPTURE:
        case self::PAYMENT_CARD_CANCEL:
            $this->checkGeneralInfo();
            break;
        case self::PAYMENT_CARD_COMPLETE:
            $request = new Request\Complete($params);
            break;
        case self::PAYMENT_STATUS:
            $request = new Request\PaymentStatus($params);
            break;
        case 'default':
            throw new ProcessException("Action: {$this->action} not supported yet");
        }
    }

    public function checkGeneralInfo()
    {}

    public function checkPaymentInfo()
    {}

    public function checkCustomerInfo()
    {}

    public function checkCardInfo()
    {}

    public function checkCustomFields(array $fields)
    {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $this->params)) {
                throw new ProcessException("Required field {$field} not found in request");
            }
        }
    }
}
