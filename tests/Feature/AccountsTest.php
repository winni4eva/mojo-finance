<?php

namespace Tests\Feature;

use App\Models\AccountType;
use App\Models\Pipelines\AccountCreatingPipeline;
use App\Models\User;
use Database\Seeders\AccountSeeder;
use Database\Seeders\AccountTypeSeeder;
use Illuminate\Support\Facades\Event;
use Tests\FeatureTestCase;

class AccountsTest extends FeatureTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser();
        $this->seed([
            AccountTypeSeeder::class,
            AccountSeeder::class,
        ]);
    }

    public function test_user_can_access_accounts()
    {
        $response = $this->actingAs($this->user)->getJson('/api/v1/accounts');

        $response->assertOk();
        $response->assertJsonCount(count: 2, key: 'data');
        $response->assertJsonPath('data.0.relationships.account_type.attributes.name', 'savings');
        $response->assertJsonPath('data.1.relationships.account_type.attributes.name', 'checking');
    }

    public function test_user_can_create_account()
    {
        $accountType = AccountType::first();
        $accountPayload = [
            'amount' => 1455.54,
            'account_type' => $accountType->id,
        ];
        $user = User::factory()->create();
        Event::fake();

        $response = $this->actingAs($user)->postJson('/api/v1/accounts', $accountPayload);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'attributes' => [
                    'amount',
                    'account_number',
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
                    'account_type' => [
                        'id',
                        'attributes' => [
                            'name',
                        ],
                    ],
                ],
            ],
        ]);
        Event::assertDispatched(AccountCreatingPipeline::class, 1);
    }
}
