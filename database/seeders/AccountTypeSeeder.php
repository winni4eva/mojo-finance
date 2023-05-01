<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Database\Factories\AccountTypeFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        AccountType::factory()->create();
    }
}
