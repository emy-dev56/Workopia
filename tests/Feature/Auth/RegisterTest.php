<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_register(): void
    {
        $user = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->post('/register', $user);
        $response->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
        ]);
        $this->assertDatabaseHas('users', [
            'name' => $user['name'],
        ]);
    }
}
