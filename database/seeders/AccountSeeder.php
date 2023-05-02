<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        AccountType::all()->each(function ($accountType) use ($user) {
            Account::factory()->create(['account_type_id' => $accountType->id, 'user_id' => $user->id]);
        });
    }
}
