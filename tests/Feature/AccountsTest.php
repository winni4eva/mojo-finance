<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountsTest extends TestCase
{
    use RefreshDatabase, HttpResponses;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_user_cannot_access_accounts()
    {
        $response = $this->get('/api/accounts', ['Accept' => 'application/json']);

        $response->assertStatus(self::UNAUTHORIZED_RESPONSE_CODE)
            ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_authenticated_user_can_access_accounts()
    {
        $response = $this->actingAs($this->user)->get('/api/accounts', ['Accept' => 'application/json']);
        $response->assertStatus(self::SUCCESS_RESPONSE_CODE);
    }

    public function test_authenticated_user_can_get_all_related_accounts()
    {
        $randomUser = User::factory()->create();
        Account::factory(count: 3)->create([
            'user_id' => $randomUser->id,
        ]);
        Account::factory(count: 2)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get('/api/accounts', ['Accept' => 'application/json']);

        $response->assertStatus(self::SUCCESS_RESPONSE_CODE)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'attributes' => [
                            'amount',
                            'created_at',
                            'updated_at',
                        ],
                        'relationships' => [
                            'user' => [
                                'id',
                                'attributes' => [
                                    'first_name',
                                    'other_name',
                                    'last_name',
                                    'email',
                                    'created_at',
                                    'updated_at',
                                ],
                                'relationships',
                            ],
                        ],
                    ],
                ],
            ])
            ->assertJsonCount(count: 2, key: 'data');
    }

    public function test_can_store_user_account()
    {
        $accountPayload = ['amount' => 1455.54];

        $response = $this->actingAs($this->user)->post('/api/accounts', $accountPayload, ['Accept' => 'application/json']);

        $response->assertStatus(self::CREATED_RESPONSE_CODE)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'attributes' => [
                        'amount',
                        'created_at',
                        'updated_at',
                    ],
                    'relationships' => [
                        'user' => [
                            'id',
                            'attributes' => [
                                'first_name',
                                'other_name',
                                'last_name',
                                'email',
                                'created_at',
                                'updated_at',
                            ],
                            'relationships',
                        ],
                    ],
                ],
            ]);
    }
}
