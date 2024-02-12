<?php

namespace Tests\Feature;

use App\Jobs\ProcessTransaction;
use App\Jobs\ScheduleTransaction;
use App\Models\Account;
use App\Models\AccountType;
use Database\Seeders\AccountSeeder;
use Database\Seeders\AccountTypeSeeder;
use Illuminate\Support\Facades\Queue;
use Tests\FeatureTestCase;

class TransactionsTest extends FeatureTestCase
{
    protected $user;

    protected $userAccounts;

    protected $debitAccount;

    protected $creditAccount;

    const ERROR_STATUS = 'An error has occurred...';

    const SUCCESS_STATUS = 'Request was successful.';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createSingleUser();
        $this->seed([
            AccountTypeSeeder::class,
            AccountSeeder::class,
        ]);
        $this->userAccounts = Account::where('user_id', $this->user->id)->get();
        $this->debitAccount = $this->userAccounts->first();
        $this->creditAccount = $this->userAccounts->get(1);
    }

    public function test_transaction_is_processed_when_schedule_is_off()
    {
        $postData = [
            'credit_account' => $this->creditAccount->id,
            'amount' => 10,
        ];
        Queue::fake();

        $response = $this->actingAs($this->user)
                        ->postJson("/api/v1/accounts/{$this->debitAccount->id}/transactions", $postData);

        $response->assertCreated();
        $response->assertJson([
            'status' => self::SUCCESS_STATUS,
            'message' => 'Transaction processing initiated successfully',
            'data' => '',
        ]);

        Queue::assertPushed(ProcessTransaction::class, 1);
        Queue::assertNotPushed(ScheduleTransaction::class);
    }

    public function test_transaction_is_scheduled_when_schedule_is_on()
    {
        $postData = [
            'credit_account' => $this->creditAccount->id,
            'amount' => 10,
            'schedule' => 1,
            'period' => '2023-05-26 16:04:00',
        ];
        Queue::fake();

        $response = $this->actingAs($this->user)
                        ->postJson("/api/v1/accounts/{$this->debitAccount->id}/transactions", $postData);

        $response->assertCreated();
        $response->assertJson([
            'status' => self::SUCCESS_STATUS,
            'message' => 'Transaction scheduled successfully',
            'data' => '',
        ]);

        Queue::assertPushed(ScheduleTransaction::class, 1);
        Queue::assertNotPushed(ProcessTransaction::class);
    }

    public function test_transaction_post_should_return_errors_when_debit_account_doesnt_belong_to_user()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $anotherUser = $this->createSingleUser();
        $accountType = AccountType::first();
        $anotherUsersAccount = Account::factory()->create([
            'account_type_id' => $accountType->id, 'user_id' => $anotherUser->id
        ]);

        $postData = [
            'credit_account' => $this->creditAccount->id,
            'amount' => 1034,
        ];

        $response = $this->actingAs($this->user)
                        ->postJson("/api/v1/accounts/{$anotherUsersAccount->id}/transactions", $postData);

        $response->assertForbidden();
        $response->assertJson([
            'status' => SELF::ERROR_STATUS,
            'message' => 'You are not authorized to make this request',
            'data' => '',
        ]);
    }

    public function test_transaction_post_should_return_errors_when_schedule_period_is_not_a_valid_date()
    {
        $postData = [
            'credit_account' => $this->creditAccount->id,
            'amount' => 1034,
            'schedule' => 1,
            'period' => '&^%$-05-WE 16:04:00',
        ];

        $response = $this->actingAs($this->user)
                        ->postJson("/api/v1/accounts/{$this->debitAccount->id}/transactions", $postData);

        $response->assertForbidden();
        $response->assertJson([
            'status' => SELF::ERROR_STATUS,
            'message' => 'The period does not match the format Y-m-d H:i:s.',
            'data' => '',
        ]);
    }

    public function test_transaction_post_should_return_errors_when_amount_is_not_a_number()
    {
        $postData = [
            'credit_account' => $this->creditAccount->id,
            'amount' => 'QWERTY',
        ];

        $response = $this->actingAs($this->user)
                        ->postJson("/api/v1/accounts/{$this->debitAccount->id}/transactions", $postData);

        $response->assertForbidden();
        $response->assertJson([
            'status' => SELF::ERROR_STATUS,
            'message' => 'The amount must be a valid currency value.',
            'data' => '',
        ]);
    }

    public function test_transaction_post_should_return_errors_when_account_does_not_exist()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $postData = [
            'credit_account' => 123456789,
            'amount' => 101,
        ];

        $response = $this->actingAs($this->user)
                        ->postJson("/api/v1/accounts/{$this->debitAccount->id}/transactions", $postData);

        $response->assertForbidden();
        $response->assertJson([
            'status' => SELF::ERROR_STATUS,
            'message' => 'Credit account does not exist',
            'data' => '',
        ]);
    }

    public function test_transaction_post_should_return_errors_when_credit_and_debit_accouts_aree_the_same()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $postData = [
            'credit_account' => $this->debitAccount->id,
            'amount' => 101,
        ];

        $response = $this->actingAs($this->user)
                        ->postJson("/api/v1/accounts/{$this->debitAccount->id}/transactions", $postData);

        $response->assertForbidden();
        $response->assertJson([
            'status' => SELF::ERROR_STATUS,
            'message' => 'Debit and credit accounts are the same',
            'data' => '',
        ]);
    }
}
