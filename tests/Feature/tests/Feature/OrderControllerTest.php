<?php

namespace Tests\Feature;


use Tests\TestCase;


use App\Models\User;

use App\Models\Order;


use Illuminate\Foundation\Testing\RefreshDatabase;



class OrderControllerTest extends TestCase
{

    use RefreshDatabase;



    private function authUser()
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





    public function test_user_can_create_order(): void
    {

        [$user, $token] =
            $this->authUser();



        $response =
            $this->withToken($token)
                ->postJson(
                    '/api/v1/orders',
                    [
                        'items' => [
                            [
                                'product_name' => 'Laptop',
                                'quantity' => 2,
                                'price' => 500
                            ]
                        ]
                    ]
                );



        $response
            ->assertCreated();


        $this->assertDatabaseHas(
            'orders',
            [
                'user_id' => $user->id,
                'total' => 1000
            ]
        );

    }





    public function test_user_can_view_orders(): void
    {


        [$user, $token] =
            $this->authUser();



        Order::factory()
            ->create([
                'user_id' => $user->id
            ]);



        $response =
            $this->withToken($token)
                ->getJson(
                    '/api/v1/orders'
                );



        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ]);

    }





    public function test_user_can_update_order(): void
    {


        [$user, $token] =
            $this->authUser();



        $order =
            Order::factory()
                ->create([
                    'user_id' => $user->id
                ]);



        $response =
            $this->withToken($token)
                ->putJson(
                    "/api/v1/orders/{$order->id}",
                    [
                        'order_status' => 'confirmed'
                    ]
                );



        $response
            ->assertStatus(200);



        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'order_status' => 'confirmed'
            ]
        );

    }





    public function test_user_can_delete_order(): void
    {


        [$user, $token] =
            $this->authUser();



        $order =
            Order::factory()
                ->create([
                    'user_id' => $user->id
                ]);



        $response =
            $this->withToken($token)
                ->deleteJson(
                    "/api/v1/orders/{$order->id}"
                );



        $response
            ->assertStatus(204);

    }

}