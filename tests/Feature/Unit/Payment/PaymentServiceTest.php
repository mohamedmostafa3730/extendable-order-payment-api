<?php

namespace Tests\Unit\Payment;


use Tests\TestCase;

use App\Services\Payment\PaymentService;

use App\Services\Payment\PaymentGatewayManager;

use App\Models\Order;

use App\Models\Payment;

use App\Enums\OrderStatus;

use App\Enums\PaymentMethod;

use App\Enums\PaymentStatus;

use Illuminate\Validation\ValidationException;



class PaymentServiceTest extends TestCase
{


    private PaymentService $service;



    protected function setUp(): void
    {

        parent::setUp();


        $this->service =
            app(PaymentService::class);

    }



    public function test_successful_payment_creates_payment(): void
    {


        $order =
            Order::factory()
                ->confirmed()
                ->create([
                    'total' => 100
                ]);



        $payment =
            $this->service->process(
                $order,
                PaymentMethod::Cash,
                100
            );



        $this->assertDatabaseHas(
            'payments',
            [
                'order_id' => $order->id,
                'amount' => 100
            ]
        );


        $this->assertEquals(
            PaymentStatus::Paid,
            $payment->payment_status
        );

    }





    public function test_pending_order_cannot_be_paid(): void
    {


        $order =
            Order::factory()
                ->create([
                    'order_status' => OrderStatus::Pending,
                    'total' => 100
                ]);



        $this->expectException(
            ValidationException::class
        );



        $this->service->process(
            $order,
            PaymentMethod::Cash,
            100
        );

    }




    public function test_wrong_amount_is_rejected(): void
    {


        $order =
            Order::factory()
                ->confirmed()
                ->create([
                    'total' => 100
                ]);



        $this->expectException(
            ValidationException::class
        );



        $this->service->process(
            $order,
            PaymentMethod::Cash,
            50
        );

    }





    public function test_duplicate_payment_is_rejected(): void
    {


        $order =
            Order::factory()
                ->confirmed()
                ->create([
                    'total' => 100
                ]);



        Payment::factory()
            ->paid()
            ->create([
                'order_id' => $order->id
            ]);



        $this->expectException(
            ValidationException::class
        );



        $this->service->process(
            $order,
            PaymentMethod::Cash,
            100
        );

    }

}