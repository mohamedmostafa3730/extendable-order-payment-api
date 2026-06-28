<?php

namespace App\Contracts\Payment;

use App\DTOs\Payment\PaymentDataDTO;
use App\DTOs\Payment\PaymentResultDTO;

interface PaymentGatewayInterface
{
    public function pay(PaymentDataDTO $payment): PaymentResultDTO;
}