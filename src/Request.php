<?php

namespace ecommpay;

/**
 * Request 
 * 
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com> 
 * @license PHP Version 7+
 */
class Request
{
    //payment block
    const PAYMENT_STATUS = 'payment/status';

    //payment_card block
    const PAYMENT_CARD_SALE = 'payment/card/sale';
    const PAYMENT_CARD_REFUND = 'payment/card/refund';
    const PAYMENT_CARD_AUTH = 'payment/card/auth';
    const PAYMENT_CARD_CAPTURE = 'payment/card/capture';
    const PAYMENT_CARD_COMPLETE = 'payment/card/complete';
    const PAYMENT_CARD_CANCEL = 'payment/card/cancel';

    /**
     * Get JSON Request
     * 
     * @param string $action Request action
     * @param array $params Request params
     * @throws ProcessException
     * @return ArrayObject
     */
    public static function get(string $action, array $params) : ArrayObject
    {
        $request = null;
        switch ($action)
        {
        case self::PAYMENT_CARD_SALE:
            $request = new Request\Sale($params);
            break;
        case self::PAYMENT_CARD_REFUND:
            $request = new Request\Refund($params);
            break;
        case self::PAYMENT_CARD_AUTH:
            $request = new Request\Auth($params);
            break;
        case self::PAYMENT_CARD_CAPTURE:
            $request = new Request\Capture($params);
            break;
        case self::PAYMENT_CARD_COMPLETE:
            $request = new Request\Complete($params);
            break;
        case self::PAYMENT_CARD_CANCEL:
            $request = new Request\Cancel($params);
            break;
        case self::PAYMENT_STATUS:
            $request = new Request\PaymentStatus($params);
            break;
        case 'default':
            throw new ProcessException("Action: {$action} not supported yet");
        }
        return $request;
    }

    /**
     * Get list of permitted actions
     * 
     * @return array
     */
    public static function getPermittedActions() : array
    {
        return [
            self::PAYMENT_STATUS            => self::PAYMENT_STATUS,
            self::PAYMENT_CARD_AUTH         => self::PAYMENT_CARD_AUTH,
            self::PAYMENT_CARD_CANCEL       => self::PAYMENT_CARD_CANCEL,
            self::PAYMENT_CARD_CAPTURE      => self::PAYMENT_CARD_CAPTURE,
            self::PAYMENT_CARD_COMPLETE     => self::PAYMENT_CARD_COMPLETE,
            self::PAYMENT_CARD_REFUND       => self::PAYMENT_CARD_REFUND,
            self::PAYMENT_CARD_SALE         => self::PAYMENT_CARD_SALE,
        ];
    }
}

