<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(['savings', 'checking'])->each(function ($accountType) {
            AccountType::factory()->create(['name' => $accountType]);
        });
    }
}
