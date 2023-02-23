<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_accounts()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('http://127.0.0.1:8000/api/accounts');

        $response->assertStatus(200);
    }
}
