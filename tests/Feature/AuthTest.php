<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_register_account()
    {
        $registerationDetails = [
            "first_name" => "Taylor",
            "other_name" => "Kofi",
            "last_name" => "Otwell",
            "email" => "taylor@test.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $response = $this->postJson('/api/v1/register', $registerationDetails);
    
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            "status",
            "message",
            "data" => [
                "user" => [
                    "first_name",
                    "other_name",
                    "last_name",
                    "email",
                    "updated_at",
                    "created_at",
                    "id"
                ],
                "token"
            ]
        ]);
        $response->assertJsonPath('data.user.email', $registerationDetails['email']);
        $response->assertJsonPath('data.user.first_name', $registerationDetails['first_name']);
        $response->assertJsonPath('data.user.other_name', $registerationDetails['other_name']);
        $response->assertJsonPath('data.user.last_name', $registerationDetails['last_name']);
    }
}
