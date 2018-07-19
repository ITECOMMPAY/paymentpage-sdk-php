<?php

namespace ecommpay\Request;

use ecommpay\Request;
use ecommpay\ProcessException;

/**
 * Validator
 *
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com>
 * @license PHP Version 7+
 */
class Validator
{
    // regexp block
    const CURRENCY_REGEXP = '^[A-Z]{3}$';
    const COUNTRY_REGEXP = '^[A-Z]{2}$';
    const PHONE_REGEXP = '^[0-9]{4,24}$';
    const DAY_OF_BIRTH_REGEXP = '^\\d{2}-\\d{2}-\\d{4}$';
    const CARDHOLDER_REGEXP = "^[a-zA-Zа-яА-ЯёрстуфхцчшщъыьэюЁРСТУФХЦЧШЩЪЫЬЭЮ0-9\\s\\-.']+$";
    const CVV_REGEXP = '^[0-9]{3,4}$';

    // custom block
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_BOOL = 'bool';
    const REQUIRED = 'required';

    /**
     * Request action
     *
     * @var string
     */
    private $action;

    /**
     * Request params
     *
     * @var array
     */
    private $params;

    /**
     * General info structure
     *
     * @var array
     */
    public static $generalInfo = [
        'project_id' => self::TYPE_INTEGER, // e. g. [fieldName] => [dataType => required]
        'payment_id' => self::TYPE_STRING,
        'terminal_callback_url' => self::TYPE_STRING,
        self::REQUIRED => ['project_id', 'payment_id'],
    ];

    /**
     * Payment info structure
     *
     * @var array
     */
    public static $paymentInfo = [
        'amount' => self::TYPE_INTEGER,
        'currency' => self::TYPE_STRING,
        'description' => self::TYPE_STRING,
        'get_route' => self::TYPE_BOOL,
        'extra_param' => self::TYPE_STRING,
        self::REQUIRED => ['amount', 'currency'],
    ];

    /**
     * Customer info structure 
     *
     * @var array
     */
    public static $customerInfo = [
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

    /**
     * Card info structure
     *
     * @var mixed
     * @access private
     */
    public static $cardInfo = [
        'pan' => self::TYPE_STRING,
        'year' => self::TYPE_INTEGER,
        'month' => self::TYPE_INTEGER,
        'card_holder' => self::TYPE_STRING,
        'cvv' => self::TYPE_STRING,
        'save' => self::TYPE_BOOL,
        'stored_card_type' => self::TYPE_INTEGER,
        self::REQUIRED => ['pan', 'year', 'month', 'card_holder', 'cvv'],
    ];

    /**
     * __construct
     *
     * @param string $action Request action
     * @param array $params Request params
     */
    public function __construct(string $action, array $params)
    {
        $this->action = $action;
        $this->params = $params;
    }

    /**
     * Check request params
     * @throws ProcessException
     */
    public function check()
    {
        switch ($this->action) {
            case Request::PAYMENT_CARD_SALE:
            case Request::PAYMENT_CARD_AUTH:
                $this->checkGeneralInfo();
                $this->checkCardInfo();
                $this->checkCustomerInfo();
                $this->checkPaymentInfo();
            break;
            case Request::PAYMENT_CARD_REFUND:
                $this->checkGeneralInfo();
            $this->checkPaymentInfo();
            break;
            case Request::PAYMENT_CARD_CAPTURE:
            case Request::PAYMENT_CARD_CANCEL:
                $this->checkGeneralInfo();
            break;
            case Request::PAYMENT_CARD_COMPLETE:
                $this->checkGeneralInfo();
                $this->commonCheck(['pares' => self::TYPE_STRING, 'md' => self::TYPE_STRING, self::REQUIRED => ['pares', 'md']]);
            break;
            case Request::PAYMENT_STATUS:
                $this->checkGeneralInfo();
                $this->commonCheck(['destination' => self::TYPE_STRING, self::REQUIRED => []]);
            break;
            case 'default':
                throw new ProcessException("Action: {$this->action} not supported yet");
        }
    }

    /**
     * Check general structure
     */
    public function checkGeneralInfo()
    {
        $this->commonCheck(self::$generalInfo);
        $this->maxLengthCheck(['payment_id', 'terminal_callback_url']);
    }

    /**
     * Check payment structure
     */
    public function checkPaymentInfo()
    {
        $this->commonCheck(self::$paymentInfo);
        $this->maxLengthCheck(['description', 'extra_param']);
        $this->regexpCheck(['currency' => self::CURRENCY_REGEXP]);
    }

    /**
     * Check customer structure
     */
    public function checkCustomerInfo()
    {
        $this->commonCheck(self::$customerInfo);
        $fields = array_keys(self::$customerInfo);
        unset($fields[self::REQUIRED]);
        unset($fields['save']);
        $this->maxLengthCheck($fields);
        $this->regexpCheck(['country' => self::COUNTRY_REGEXP, 'phone' => self::PHONE_REGEXP, 'day_of_birth' => self::DAY_OF_BIRTH_REGEXP]);
    }

    /**
     * Check card structure
     */
    public function checkCardInfo()
    {
        $this->commonCheck(self::$cardInfo);
        $this->maxLengthCheck(['pan'], 32);
        $this->regexpCheck(['card_holder' => self::CARDHOLDER_REGEXP, 'cvv' => self::CVV_REGEXP]);
    }

    /**
     * Common check
     *
     * @param array $struct Structure
     * @throws ProcessException
     */
    private function commonCheck(array $struct)
    {
        $required = $struct[self::REQUIRED];
        $paramsDiff = array_diff($required, array_keys($this->params));

        if (count($paramsDiff) > 0) {
            throw new ProcessException('Required fields ' . var_export($paramsDiff, true) . ' not present in source request');
        }

        foreach ($this->params as $fieldName => $fieldValue) {
            if (array_key_exists($fieldName, $struct)) {
                $fieldValueType = gettype($fieldValue);
                if ($struct[$fieldName] == self::TYPE_STRING && !is_string($fieldValue)) {
                    throw new ProcessException("Field name: {$fieldName} have to be STRING type. Actual type: {$fieldValueType}");
                } elseif ($struct[$fieldName] == self::TYPE_INTEGER) {
                    if (!is_int($fieldValue)) {
                        throw new ProcessException("Field name: {$fieldName} have to be INTEGER type. Actual type: {$fieldValueType}");
                    }
                    if ($fieldValue <= 0) {
                        throw new ProcessException("Integer field name: {$fieldName} has negative or 0 value");
                    }
                } elseif ($struct[$fieldName] == self::TYPE_BOOL && !is_bool($fieldValue)) {
                    throw new ProcessException("Field name: {$fieldName} have to be BOOL type. Actual type: {$fieldValueType}");
                }
            }
        }
    }

    /**
     * Checking maximum length
     *
     * @param array $fields Fields
     * @param int $maxLength Maximum field length
     * @throws ProcessException
     */
    private function maxLengthCheck(array $fields, int $maxLength = 255)
    {
        foreach ($fields as $field) {
            if (array_key_exists($field, $this->params) && strlen($this->params[$field]) > $maxLength) {
                throw new ProcessException("Length of Param: {$field} with value {$this->params[$field]} more than {$maxLength} symbols");
            }
        }
    }

    /**
     * Check field regular expression
     *
     * @param array $conditions [fieldName => regular_expression]
     * @throws ProcessException
     */
    private function regexpCheck(array $conditions)
    {
        foreach ($conditions as $name => $regexp) {
            if (array_key_exists($name, $this->params) && !preg_match('/' . $regexp . '/', $this->params[$name])) {
                throw new ProcessException("Param name {$name} with value {$this->params[$name]} doesnt match regular expression {$regexp}");
            }
        }
    }
}
