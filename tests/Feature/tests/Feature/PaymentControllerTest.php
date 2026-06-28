<?php


namespace Tests\Feature;


use Tests\TestCase;


use App\Models\User;

use App\Models\Order;


use App\Enums\OrderStatus;


use Illuminate\Foundation\Testing\RefreshDatabase;



class PaymentControllerTest extends TestCase
{

    use RefreshDatabase;



    private function login()
    {

        $user =
            User::factory()->create();


        $token =
            auth('api')->login($user);



        return [
            $user,
            $token
        ];

    }





    public function test_user_can_process_payment(): void
    {


        [$user, $token] =
            $this->login();



        $order =
            Order::factory()
                ->confirmed()
                ->create([
                    'user_id' => $user->id,
                    'total' => 100
                ]);



        $response =
            $this->withToken($token)
                ->postJson(
                    "/api/v1/orders/{$order->id}/payments",
                    [
                        'order_id' => $order->id,
                        'payment_method' => 'cash',
                        'amount' => 100
                    ]
                );



        $response
            ->assertCreated();



        $this->assertDatabaseHas(
            'payments',
            [
                'order_id' => $order->id,
                'amount' => 100
            ]
        );

    }





    public function test_payment_fails_for_pending_order(): void
    {


        [$user, $token] =
            $this->login();



        $order =
            Order::factory()
                ->create([
                    'user_id' => $user->id,
                    'order_status' => OrderStatus::Pending,
                    'total' => 100
                ]);



        $response =
            $this->withToken($token)
                ->postJson(
                    "/api/v1/orders/{$order->id}/payments",
                    [
                        'order_id' => $order->id,
                        'payment_method' => 'cash',
                        'amount' => 100
                    ]
                );



        $response
            ->assertStatus(422);

    }





    public function test_payment_amount_must_match_order_total(): void
    {


        [$user, $token] =
            $this->login();



        $order =
            Order::factory()
                ->confirmed()
                ->create([
                    'user_id' => $user->id,
                    'total' => 100
                ]);



        $response =
            $this->withToken($token)
                ->postJson(
                    "/api/v1/orders/{$order->id}/payments",
                    [
                        'order_id' => $order->id,
                        'payment_method' => 'cash',
                        'amount' => 50
                    ]
                );



        $response
            ->assertStatus(422);

    }

}