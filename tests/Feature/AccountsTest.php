<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use App\Traits\HttpResponseTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AccountsTest extends TestCase
{
    use RefreshDatabase, HttpResponseTrait;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_user_cannot_access_accounts()
    {
        $response = $this->get('/api/accounts', ['Accept' => 'application/json']);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_authenticated_user_can_access_accounts()
    {
        $response = $this->actingAs($this->user)->get('/api/accounts', ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_OK);
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

        $response->assertStatus(Response::HTTP_OK)
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

        $response->assertStatus(Response::HTTP_CREATED)
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
