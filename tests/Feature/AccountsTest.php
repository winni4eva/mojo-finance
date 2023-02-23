<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_get_accounts()
    {
        $response = $this->get('/api/accounts');

        $response->assertStatus(500);
    }
    public function test_authenticated_user_can_get_accounts()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/accounts');

        $response->assertStatus(200);
    }
}
