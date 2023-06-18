<?php

namespace Database\Seeders;

use App\Enums\AccountType as EnumsAccountType;
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
        collect([EnumsAccountType::SAVINGS, EnumsAccountType::CHECKING])->each(function ($accountType) {
            AccountType::factory()->create(['name' => $accountType]);
        });
    }
}
