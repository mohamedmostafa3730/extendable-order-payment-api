<?php

namespace App\Gateways\Payment;

use App\Contracts\Payment\PaymentGatewayInterface;
use App\DTOs\Payment\PaymentDataDTO;
use App\DTOs\Payment\PaymentResultDTO;
use App\Enums\PaymentStatus;
use Illuminate\Support\Str;

class BankTransferGateway implements PaymentGatewayInterface
{

    public function pay(PaymentDataDTO $payment): PaymentResultDTO
    {
        return new PaymentResultDTO(
            success: true,
            status: PaymentStatus::Pending,
            reference: (string) Str::uuid(),
            message: 'Bank transfer initiated and awaiting confirmation.'
        );
    }

}