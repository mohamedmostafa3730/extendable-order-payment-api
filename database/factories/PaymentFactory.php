<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Support\Str;
/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'id' =>
                (string) Str::uuid(),


            'order_id' =>
                Order::factory(),


            'payment_reference' =>
                (string) Str::uuid(),


            'payment_method' =>
                PaymentMethod::CreditCard,


            'payment_status' =>
                PaymentStatus::Pending,


            'amount' =>
                fake()->randomFloat(
                    2,
                    10,
                    1000
                ),

        ];
    }
    public function paid(): static
    {

        return $this->state(

            fn() => [

                'payment_status' =>
                    PaymentStatus::Paid

            ]

        );

    }

    public function cash(): static
    {

        return $this->state(

            fn() => [

                'payment_method' =>
                    PaymentMethod::Cash

            ]

        );

    }




    public function bankTransfer(): static
    {

        return $this->state(

            fn() => [

                'payment_method' =>
                    PaymentMethod::BankTransfer

            ]

        );

    }

}
