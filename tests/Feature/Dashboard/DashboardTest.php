<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_dashboard_loads_for_logged_in_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('dashboard'));
        $response->assertSessionDoesntHaveErrors();
        $response->assertStatus(200);
    }
}
