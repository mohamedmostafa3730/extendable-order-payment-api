<?php

namespace Tests\Feature;


use Tests\TestCase;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;


class AuthControllerTest extends TestCase
{

    use RefreshDatabase;



    public function test_user_can_register(): void
    {

        $response = $this->postJson(
            '/api/v1/register',
            [
                'name' => 'Mohamed',
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]
        );


        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'User registered successfully.'
            ]);



        $this->assertDatabaseHas(
            'users',
            [
                'email' => 'test@example.com'
            ]
        );

    }





    public function test_user_can_login(): void
    {

        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);



        $response =
            $this->postJson(
                '/api/v1/login',
                [
                    'email' => 'test@example.com',
                    'password' => 'password123'
                ]
            );



        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token'
            ]);

    }





    public function test_user_cannot_login_with_wrong_password(): void
    {


        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);



        $response =
            $this->postJson(
                '/api/v1/login',
                [
                    'email' => 'test@example.com',
                    'password' => 'wrong-password'
                ]
            );



        $response
            ->assertStatus(422);

    }





    public function test_authenticated_user_can_logout(): void
    {

        $user =
            User::factory()->create();



        $token =
            auth('api')
                ->login($user);



        $response =
            $this->withHeader(
                'Authorization',
                "Bearer $token"
            )
                ->postJson(
                    '/api/v1/logout'
                );



        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Logged out successfully.'
            ]);

    }

}