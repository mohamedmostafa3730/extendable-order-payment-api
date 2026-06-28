<?php

namespace Tests\Unit\Payment;


use Tests\TestCase;
use App\Gateways\Payment\WalletGateway;
use App\DTOs\Payment\PaymentDataDTO;
use App\Models\Order;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;


class WalletGatewayTest extends TestCase
{

    public function test_wallet_gateway_processes_payment(): void
    {

        $gateway = new WalletGateway();


        $result = $gateway->pay(
            new PaymentDataDTO(
                Order::factory()->create(),
                50,
                PaymentMethod::Wallet
            )
        );


        $this->assertTrue(
            $result->success
        );


        $this->assertEquals(
            PaymentStatus::Paid,
            $result->status
        );

    }

}