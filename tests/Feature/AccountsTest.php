<?php

namespace Tests\Feature;

use App\Models\AccountType;
use App\Models\Pipelines\AccountCreatingPipeline;
use Database\Seeders\AccountSeeder;
use Database\Seeders\AccountTypeSeeder;
use Illuminate\Support\Facades\Event;
use Tests\FeatureTestCase;

class AccountsTest extends FeatureTestCase
{
    protected $user;

    const ACCOUNTS_ENDPOINT = '/api/v1/accounts';

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
        $response = $this->actingAs($this->user)->getJson(self::ACCOUNTS_ENDPOINT);

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

        $response = $this->actingAs($user)->postJson(self::ACCOUNTS_ENDPOINT, $accountPayload);

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

    public function test_user_account_create_returns_error_when_account_type_already_exists(): void
    {
        $accountType = AccountType::first();
        $accountPayload = [
            'amount' => 1455.54,
            'account_type' => $accountType->id,
        ];

        $response = $this->actingAs($this->user)->postJson(self::ACCOUNTS_ENDPOINT, $accountPayload);

        $response->assertForbidden();
        $response->assertJson([
            'status' => 'An error has occurred...',
            'message' => 'This account type already exists for this user.',
            'data' => '',
        ]);
    }

    public function test_user_account_create_returns_error_when_aamount_is_invalid(): void
    {
        $user = $this->createSingleUser();
        $accountType = AccountType::first();
        $accountPayload = [
            'amount' => 'QWERTY',
            'account_type' => $accountType->id,
        ];

        $response = $this->actingAs($user)->postJson(self::ACCOUNTS_ENDPOINT, $accountPayload);

        $response->assertForbidden();
        $response->assertJson([
            'status' => 'An error has occurred...',
            'message' => 'The amount must be a valid currency value.',
            'data' => '',
        ]);
    }
}
