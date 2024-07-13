<?php

namespace Tests\Feature;

use Tests\TestCase;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    //     public function test_example(): void
    //     {
    //         $response = $this->get('/');

    //         $response->assertStatus(200);
    //     }

    public function testRegisterSuccess()
    {
        // $this->seed([UserSeeder::class]);
        $this->post('/api/users/register', [
            'username' => 'giansoniaputra',
            'name' => 'Gian Sonia',
            'password' => 'admin12345',
        ])->assertStatus(201)
            ->assertJson([
                'success' => [
                    "message" => "Registration successful!"
                ]
            ]);
    }

    public function testRegisterValidationError()
    {
        $this->post('/api/users/register', [
            'username' => '',
            'name' => '',
            'password' => '',
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    "name" => [
                        "The name field is required."
                    ],
                    "username" => [
                        "The username field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ]
                ]
            ]);
    }

    public function testUsernameAlreadyExists()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/register', [
            'username' => 'giansoniaputra',
            'name' => 'Gian Sonia',
            'password' => 'admin12345',
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => [
                        "The username has already been taken."
                    ]
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'giansoniaputra',
            'password' => 'admin12345',
        ])->assertStatus(200)
            ->assertJson([
                "success" => [
                    "message" => "Login successful!"

                ]
            ]);
    }

    public function testLoginFailed()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'giansoniaputra',
            'password' => 'admin1234',
        ])->assertStatus(401)
            ->assertJson([
                "error" => "Unauthorized!"
            ]);
    }

    public function testLoginNull()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => '',
            'password' => '',
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => [
                        "The username field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ]
                ]
            ]);
    }

    public function testLogout()
    {
        $this->testLoginSuccess();
        $this->post('/api/users/logout')
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Successfully logged out'
            ]);
    }
}
