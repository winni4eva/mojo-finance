<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function createUser(array $fields = [])
    {
        $this->user = User::factory()->create($fields);
    }
}