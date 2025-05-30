<?php

namespace Chargebee\Cashier\Exceptions;

use Chargebee\Cashier\Payment;
use Exception;
use Throwable;

final class IncompletePayment extends Exception
{
    /**
     * The Cashier Payment object.
     */
    public readonly Payment $payment;

    /**
     * Create a new IncompletePayment instance.
     */
    public function __construct(Payment $payment, string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->payment = $payment;
    }

    /**
     * Create a new IncompletePayment instance with a `requires_action` type.
     */
    public static function requiresAction(Payment $payment): static
    {
        return new self(
            $payment,
            'Additional action is required before payment attempt can be completed.'
        );
    }
}
