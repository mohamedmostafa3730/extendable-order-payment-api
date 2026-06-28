<?php

namespace App\DTOs\Payment;

use App\Enums\PaymentStatus;

readonly class PaymentResultDTO
{
    public function __construct(
        public bool $success,
        public PaymentStatus $status,
        public string $reference,
        public string $message,
    ) {
    }
}