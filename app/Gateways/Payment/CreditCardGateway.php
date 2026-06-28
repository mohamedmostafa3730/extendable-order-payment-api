<?php

namespace App\Gateways\Payment;

use App\Contracts\Payment\PaymentGatewayInterface;
use App\DTOs\Payment\PaymentDataDTO;
use App\DTOs\Payment\PaymentResultDTO;
use App\Enums\PaymentStatus;
use Illuminate\Support\Str;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function pay(PaymentDataDTO $payment): PaymentResultDTO
    {
        // Simulate payment processing...
        sleep(1);

        return new PaymentResultDTO(
            success: true,
            status: PaymentStatus::Paid,
            reference: (string) Str::uuid(),
            message: 'Credit card payment processed successfully.'
        );
    }
}