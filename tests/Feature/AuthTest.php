<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['email' => 'jeff.way@test.com']);
    }

    /**
     * Test user registration.
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
    
        $response->assertOk();
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

    /**
     * Test user registration duplicate email validation error.
     *
     * @return void
     */
    public function test_should_throw_validation_error_when_email_is_taken()
    {
        $registerationDetails = [
            "first_name" => "Jefferey",
            "other_name" => "Kobi",
            "last_name" => "Way",
            "email" => "jeff.way@test.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];
        $errorMessage = "The email has already been taken.";

        $response = $this->postJson('/api/v1/register', $registerationDetails);
        
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            "status",
            "message",
            "data"
        ]);
        $response->assertJsonPath('message', $errorMessage);
    }

    /**
     * Test user registration no password confirmation validation error.
     *
     * @return void
     */
    public function test_should_throw_validation_error_when_password_cofirmation_is_not_provided()
    {
        $registerationDetails = [
            "first_name" => "Douglas",
            "other_name" => "Yaw",
            "last_name" => "Crockford",
            "email" => "dcrockford@test.com",
            "password" => "password"
        ];
        $errorMessage = "The password confirmation does not match.";

        $response = $this->postJson('/api/v1/register', $registerationDetails);
        
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            "status",
            "message",
            "data"
        ]);
        $response->assertJsonPath('message', $errorMessage);
    }

    /**
     * Test user login success.
     *
     * @return void
     */
    public function test_should_login_user_successfully_when_right_credentials_are_provided()
    {
        $loginDetails = [
            "email" => "jeff.way@test.com",
            "password" => "password"
        ];
        $successMesage = "User logged in successfully.";

        $response = $this->postJson('/api/v1/login', $loginDetails);

        $response->assertOk();
        $response->assertJsonStructure([
            "status",
            "message",
            "data" => [
                "user",
                "token"
            ]
        ]);
        $response->assertJsonPath('message', $successMesage);
    }

     /**
     * Test user logout success.
     *
     * @return void
     */
    public function test_should_logout_user_successfully()
    {
        Sanctum::actingAs($this->user, ['*']);
        $accessToken = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $accessToken)
                         ->postJson('/api/v1/logout');
        
        $response->assertNoContent();

        // $response = $this->withHeader('Authorization', 'Bearer ' . $accessToken)
        //                  ->get('/api/v1/accounts');
        // $response->assertUnauthorized();
    }
}
