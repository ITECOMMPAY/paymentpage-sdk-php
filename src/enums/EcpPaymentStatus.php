<?php

namespace ecommpay\enums;

use ecommpay\Callback;

final class EcpPaymentStatus
{
    /**
     * Payment completed successfully
     * @var string
     */
    const SUCCESS = 'success';

    /**
     * An internal error occurred
     * @var string
     */
    const INTERNAL_ERROR = 'internal error';

    /**
     * The payment request has expired
     * @var string
     */
    const EXPIRED = 'expired';

    /**
     * Awaiting customer actions, if the customer may perform additional attempts to make a payment
     * @var string
     */
    const AWAITING_CUSTOMER = 'awaiting customer';

    /**
     * The payment was declined
     * @var string
     */
    const DECLINE = 'decline';

    /**
     * An external error occurred during the payment process
     * @var string
     */
    const EXTERNAL_ERROR = 'external error';

    /**
     * Awaiting a request with the result of a 3-D Secure Verification
     * @var string
     */
    const AWAITING_3DS_RESULT = 'awaiting 3ds result';

    /**
     * Awaiting customer return after redirecting the customer to an external provider system
     * @var string
     */
    const AWAITING_REDIRECT_RESULT = 'awaiting redirect result';

    /**
     * Awaiting additional parameters
     * @var string
     */
    const AWAITING_CLARIFICATION = 'awaiting clarification';

    /**
     * Awaiting request for withdrawal of funds (capture) or cancellation of payment (cancel) from your project
     * @var string
     */
    const AWAITING_CAPTURE = 'awaiting capture';

    /**
     * Holding of funds (produced on authorization request) is cancelled
     * @var string
     */
    const CANCELED = 'canceled';

    /**
     * @deprecated use Callback::CANCELED instead
     * @see Callback::CANCELED
     *
     * Holding of funds (produced on authorization request) is cancelled
     * @var string
     */
    const CANCELLED = self::CANCELED;

    /**
     * Successfully completed the full refund after a successful payment
     * @var string
     */
    const REFUNDED = 'refunded';

    /**
     * Completed partial refund after a successful payment
     * @var string
     */
    const PARTIALLY_REFUNDED = 'partially refunded';

    /**
     * Payment processing at Gate
     * @var string
     */
    const PROCESSING = 'processing';

    /**
     * An error occurred while reviewing data for payment processing
     * @var string
     */
    const ERROR = 'error';

    /**
     * Refund after a successful payment before closing of the business day
     * @var string
     */
    const REVERSED = 'reversed';

    public static function values(): array
    {
        return [
            self::SUCCESS,
            self::INTERNAL_ERROR,
            self::EXPIRED,
            self::AWAITING_CUSTOMER,
            self::DECLINE,
            self::EXTERNAL_ERROR,
            self::AWAITING_3DS_RESULT,
        ];
    }
}
