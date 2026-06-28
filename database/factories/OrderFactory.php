<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),

            'user_id' => User::factory(),

            'order_status' =>
                OrderStatus::Pending,

            'total' => fake()
                ->randomFloat(
                    2,
                    10,
                    1000
                ),
        ];
    }

    public function confirmed(): static
    {

        return $this->state(
            fn() => [

                'order_status' =>
                    OrderStatus::Confirmed

            ]
        );
    }

    public function cancelled(): static
    {

        return $this->state(
            fn() => [

                'order_status' =>
                    OrderStatus::Cancelled

            ]
        );
    }
}
