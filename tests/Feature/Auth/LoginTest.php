<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_login(): void
    {
        $email = fake()->email();
        $user = User::factory()->create([
            'email' => $email,
            'password' => 'password',
        ]);
        
        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $response->assertSessionHas('success');
    }

    /**
     * A basic feature test example.
     */
    public function test_user_cannot_login_with_wrong_creds(): void
    {
        $email = fake()->email();
        $user = User::factory()->create([
            'email' => $email,
            'password' => 'password',
        ]);
        
        $response = $this->from('/login')->post('/login', [
            'email' => $email,
            'password' => 'password111',
        ]);
        $response->assertSessionHasErrors();
    }

    /**
     * A basic feature test example.
     */
    public function test_login_page(): void{
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}
