<?php

namespace Tests\Unit\Payment;

use Tests\TestCase;
use App\Gateways\Payment\PayPalGateway;
use App\DTOs\Payment\PaymentDataDTO;
use App\Models\Order;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;


class PayPalGatewayTest extends TestCase
{

    public function test_paypal_gateway_processes_payment(): void
    {

        $gateway = new PayPalGateway();


        $result = $gateway->pay(
            new PaymentDataDTO(
                Order::factory()->create(),
                200,
                PaymentMethod::PayPal
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