<?php

namespace Tests\Unit\Payment;


use Tests\TestCase;
use App\Services\Payment\PaymentGatewayManager;
use App\Enums\PaymentMethod;
use App\Gateways\Payment\CashGateway;
use App\Gateways\Payment\CreditCardGateway;


class PaymentGatewayManagerTest extends TestCase
{


    public function test_resolves_credit_card_gateway(): void
    {

        $manager =
            app(PaymentGatewayManager::class);



        $gateway =
            $manager->resolve(
                PaymentMethod::CreditCard
            );



        $this->assertInstanceOf(
            CreditCardGateway::class,
            $gateway
        );

    }



    public function test_resolves_cash_gateway(): void
    {

        $manager =
            app(PaymentGatewayManager::class);



        $gateway =
            $manager->resolve(
                PaymentMethod::Cash
            );



        $this->assertInstanceOf(
            CashGateway::class,
            $gateway
        );

    }

}