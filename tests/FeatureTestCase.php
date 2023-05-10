<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    public function createSingleUser(array $fields = [], int $count = 1): User
    {
        return User::factory()->create($fields);
    }

    public function createManyUsers(array $fields = [], int $count = 1)
    {
        return User::factory($count)->create($fields);
    }
}