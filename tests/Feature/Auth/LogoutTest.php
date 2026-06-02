<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->post(route('logout'));
        $response->assertRedirect(route('home'));        
    }
}
