<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->put(route('profile.update'), [
            'name' => 'John Doe111',
            'email' => $user->email
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe111',
        ]);
    }


    /**
     * A basic feature test example.
     */
    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->put(route('profile.update'), [
            'name' => 'John Doe111',
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe111',
        ]);
        Storage::disk('public')->assertExists(User::first()->avatar);
    }
}
