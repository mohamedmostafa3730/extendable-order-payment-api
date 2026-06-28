<?php

namespace App\DTOs\Payment;

use App\Enums\PaymentMethod;
use App\Models\Order;

readonly class PaymentDataDTO
{
    public function __construct(
        public Order $order,
        public float $amount,
        public PaymentMethod $paymentMethod,
    ) {
    }
}