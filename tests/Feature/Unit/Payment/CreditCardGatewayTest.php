<?php

namespace Tests\Unit\Payment;

use Tests\TestCase;
use App\Gateways\Payment\CreditCardGateway;
use App\DTOs\Payment\PaymentDataDTO;
use App\Models\Order;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;


class CreditCardGatewayTest extends TestCase
{

    public function test_credit_card_gateway_processes_payment(): void
    {

        $gateway = new CreditCardGateway();


        $result = $gateway->pay(
            new PaymentDataDTO(
                Order::factory()->create(),
                100,
                PaymentMethod::CreditCard
            )
        );


        $this->assertTrue(
            $result->success
        );


        $this->assertEquals(
            PaymentStatus::Paid,
            $result->status
        );


        $this->assertNotEmpty(
            $result->reference
        );

    }

}