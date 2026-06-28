<?php

namespace Tests\Unit\Payment;


use Tests\TestCase;
use App\Gateways\Payment\BankTransferGateway;
use App\DTOs\Payment\PaymentDataDTO;
use App\Models\Order;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;


class BankTransferGatewayTest extends TestCase
{

    public function test_bank_transfer_returns_pending_status(): void
    {

        $gateway = new BankTransferGateway();


        $result = $gateway->pay(
            new PaymentDataDTO(
                Order::factory()->create(),
                400,
                PaymentMethod::BankTransfer
            )
        );


        $this->assertEquals(
            PaymentStatus::Pending,
            $result->status
        );

    }

}