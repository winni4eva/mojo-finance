<?php

namespace Tests\Feature;

use App\Jobs\ProcessTransaction;
use App\Jobs\ScheduleTransaction;
use App\Models\Account;
use Database\Seeders\AccountSeeder;
use Database\Seeders\AccountTypeSeeder;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Queue;

class TransactionsTest extends FeatureTestCase
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

    public function test_transaction_is_processed_when_schedule_is_off()
    {
        $userAccounts = Account::where('user_id', $this->user->id)->get();
        $debitAccount = $userAccounts->first();
        $creditAccount = $userAccounts->get(1);

        $postData = [
            'credit_account' => $creditAccount->id,
            'amount' => 10
        ];
        Queue::fake();

        $response = $this->actingAs($this->user)->postJson("/api/v1/accounts/{$debitAccount->id}/transactions", $postData);

        $response->assertCreated();
        $response->assertJson([
            'status' => 'Request was successful.',
            'message' => 'Transaction processing initiated successfully',
            'data' => ''
        ]);
        
        Queue::assertPushed(ProcessTransaction::class, 1);
        Queue::assertNotPushed(ScheduleTransaction::class);
    }

    public function test_transaction_is_scheduled_when_schedule_is_on()
    {
        $userAccounts = Account::where('user_id', $this->user->id)->get();
        $debitAccount = $userAccounts->first();
        $creditAccount = $userAccounts->get(1);

        $postData = [
            'credit_account' => $creditAccount->id,
            'amount' => 10,
            'schedule' => 1,
            'period' => '2023-05-26 16:04:00'
        ];
        Queue::fake();

        $response = $this->actingAs($this->user)->postJson("/api/v1/accounts/{$debitAccount->id}/transactions", $postData);

        $response->assertCreated();
        $response->assertJson([
            'status' => 'Request was successful.',
            'message' => 'Transaction scheduled successfully',
            'data' => ''
        ]);
        
        Queue::assertPushed(ScheduleTransaction::class, 1);
        Queue::assertNotPushed(ProcessTransaction::class);
    }
}
