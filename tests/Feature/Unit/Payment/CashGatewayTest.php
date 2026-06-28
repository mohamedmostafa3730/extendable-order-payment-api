<?php

namespace Tests\Unit\Payment;


use Tests\TestCase;
use App\Gateways\Payment\CashGateway;
use App\DTOs\Payment\PaymentDataDTO;
use App\Models\Order;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;


class CashGatewayTest extends TestCase
{

    public function test_cash_gateway_returns_paid_status(): void
    {

        $gateway = new CashGateway();


        $result = $gateway->pay(
            new PaymentDataDTO(
                Order::factory()->create(),
                300,
                PaymentMethod::Cash
            )
        );


        $this->assertEquals(
            PaymentStatus::Paid,
            $result->status
        );

    }

}