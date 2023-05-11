<?php

namespace Tests\Feature;

use App\Http\Resources\AccountResource;
use App\Models\AccountType;
use App\Models\Pipelines\AccountCreatingPipeline;
use Database\Seeders\AccountSeeder;
use Database\Seeders\AccountTypeSeeder;
use Illuminate\Support\Facades\Event;
use Tests\FeatureTestCase;

class AccountsTest extends FeatureTestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createSingleUser();
        $this->seed([
            AccountTypeSeeder::class,
            AccountSeeder::class,
        ]);
    }

    public function test_user_can_access_accounts(): void
    {
        $response = $this->actingAs($this->user)->getJson('/api/v1/accounts');

        $response->assertOk();
        $response->assertJsonCount(count: 2, key: 'data');
        $response->assertJsonPath('data.0.relationships.account_type.attributes.name', 'savings');
        $response->assertJsonPath('data.1.relationships.account_type.attributes.name', 'checking');
    }

    public function test_user_can_create_account(): void
    {
        $accountType = AccountType::first();
        $accountPayload = [
            'amount' => 1455.54,
            'account_type' => $accountType->id,
        ];
        $user = $this->createSingleUser();
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
        //$this->assertInstanceOf(AccountResource::class, $response->getOriginalContent());
    }
}
