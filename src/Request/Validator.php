<?php

namespace ecommpay;

class Validator
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_BOOL = 'bool';
    const REQUIRED = 'required';

    private $action;
    private $params;

    private $generalInfo = [
        'project_id' => self::TYPE_INTEGER, //e. g. [fieldName] => [dataType => required]
        'payment_id' => self::TYPE_STRING,
        'terminal_callback_url' => self::TYPE_STRING,
        self::REQUIRED => ['project_id', 'payment_id'],
    ];

    private $paymentInfo = [
        'amount' => self::TYPE_INTEGER,
        'currency' => self::TYPE_STRING,
        'description' => self::TYPE_STRING,
        'get_route' => self::TYPE_BOOL,
        'extra_param' => self::TYPE_STRING,
        self::REQUIRED => ['amount', 'currency'],
    ];

    private $customerInfo = [
        'id' => self::TYPE_STRING,
        'country' => self::TYPE_STRING,
        'city' => self::TYPE_STRING,
        'state' => self::TYPE_STRING,
        'phone' => self::TYPE_STRING,
        'day_of_birth' => self::TYPE_STRING,
        'birthplace' => self::TYPE_STRING,
        'first_name' => self::TYPE_STRING,
        'middle_name' => self::TYPE_STRING,
        'last_name' => self::TYPE_STRING,
        'email' => self::TYPE_STRING,
        'browser' => self::TYPE_STRING,
        'ip_address' => self::TYPE_STRING,
        'device_type' => self::TYPE_STRING,
        'datetime' => self::TYPE_STRING,
        'screen_res' => self::TYPE_STRING,
        'session_id' => self::TYPE_STRING,
        'language' => self::TYPE_STRING,
        'zip' => self::TYPE_STRING,
        'address' => self::TYPE_STRING,
        'account_id' => self::TYPE_STRING,
        'doc_number' => self::TYPE_STRING,
        'doc_type' => self::TYPE_STRING,
        'doc_issue_date' => self::TYPE_STRING,
        'doc_issue_by' => self::TYPE_STRING,
        'ssn' => self::TYPE_INTEGER,
        'country' => self::TYPE_STRING,
        'region' => self::TYPE_STRING,
        'city' => self::TYPE_STRING,
        'address' => self::TYPE_STRING,
        'postal' => self::TYPE_STRING,
        self::REQUIRED => ['ip_address'],
    ];
   
    private $cardInfo = [
        'pan' => self::TYPE_STRING,
        'year' => self::TYPE_STRING,
        'month' => self::TYPE_STRING,
        'card_holder' => self::TYPE_STRING,
        'cvv' => self::TYPE_STRING,
        'save' => self::TYPE_STRING,
        'stored_card_type' => self::TYPE_STRING,
        self::REQUIRED => ['pan', 'year', 'month', 'card_holder', 'cvv'],
    ];

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
            $this->checkGeneralInfo();
            $this->customCheck(['pares' => self::TYPE_STRING, 'md' => self::TYPE_STRING]);
            break;
        case self::PAYMENT_STATUS:
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

    private function customCheck(array $fields)
    {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $this->params)) {
                throw new ProcessException("Required field {$field} not found in request");
            }
        }
    }
}
