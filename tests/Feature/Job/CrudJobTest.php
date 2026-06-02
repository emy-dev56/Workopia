<?php

namespace Tests\Feature\Job;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CrudJobTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_create_job_with_company_logo(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Storage::fake('public');

        $job = [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'job_type' => fake()->randomElement(['Full-Time', 'Part-time', 'Contract', 'Temporary', 'Internship', 'Volunteer', 'On-Call']),
            'remote' => fake()->boolean(),
            'requirements' => fake()->paragraph(),
            'benefits' => fake()->paragraph(),
            'tags' => implode(', ', fake()->words('5')),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zipcode' => fake()->postcode(),
            'salary' => fake()->numberBetween(1000, 10000),
            'company_name' => fake()->company(),
            'company_description' => fake()->paragraph(),
            'company_website' => fake()->url(),
            'company_logo' => UploadedFile::fake()->image('logo.jpg'),
            'contact_email' => fake()->email(),
            'contact_phone' => fake()->phoneNumber()
        ];

        $response = $this->post(route('jobs.store'), $job);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('jobs.index'));

        $this->assertDatabaseHas('job_listings', [
            'title' => $job['title']
        ]);
        Storage::disk('public')->assertExists(Job::first()->company_logo);
    }


    /**
     * A basic feature test example.
     */
    public function test_user_can_create_job_without_company_logo(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $job = [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'job_type' => fake()->randomElement(['Full-Time', 'Part-time', 'Contract', 'Temporary', 'Internship', 'Volunteer', 'On-Call']),
            'remote' => fake()->boolean(),
            'requirements' => fake()->paragraph(),
            'benefits' => fake()->paragraph(),
            'tags' => implode(', ', fake()->words('5')),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zipcode' => fake()->postcode(),
            'salary' => fake()->numberBetween(1000, 10000),
            'company_name' => fake()->company(),
            'company_description' => fake()->paragraph(),
            'company_website' => fake()->url(),
            'contact_email' => fake()->email(),
            'contact_phone' => fake()->phoneNumber()
        ];

        $response = $this->post(route('jobs.store'), $job);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('jobs.index'));

        $this->assertDatabaseHas('job_listings', [
            'title' => $job['title']
        ]);
    }

    /**
     * 
     **/
    public function test_user_can_update_job(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $newJob = ['title' => fake()->sentence()];

        $job = Job::factory()->create(['user_id' => $user->id]);
        $response = $this->put(route('jobs.update', $job), [
            'title' => $newJob['title'],
            'description' => $job->description,
            'job_type' => $job->job_type,
            'remote' => $job->remote,
            'requirements' => $job->requirements,
            'benefits' => $job->benefits,
            'tags' => $job->tags,
            'address' => $job->address,
            'city' => $job->city,
            'state' => $job->state,
            'zipcode' => $job->zipcode,
            'salary' => $job->salary,
            'company_name' => $job->company_name,
            'company_description' => $job->company_description,
            'company_website' => $job->company_website,
            'contact_email' => $job->contact_email,
            'contact_phone' => $job->contact_phone,
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('jobs.index'));

        $this->assertDatabaseHas('job_listings', [
            'title' => $newJob['title']
        ]);
    }


    /**
     * 
     **/
    public function test_user_can_update_job_with_logo(): void
    {
        Storage::fake();
        $user = User::factory()->create();
        $this->actingAs($user);

        $newJob = ['title' => fake()->sentence(), 'company_logo' => UploadedFile::fake()->image('logo.jpg')];

        $job = Job::factory()->create(['user_id' => $user->id]);
        $response = $this->put(route('jobs.update', $job), [
            'title' => $newJob['title'],
            'description' => $job->description,
            'job_type' => $job->job_type,
            'remote' => $job->remote,
            'requirements' => $job->requirements,
            'benefits' => $job->benefits,
            'tags' => $job->tags,
            'address' => $job->address,
            'city' => $job->city,
            'state' => $job->state,
            'zipcode' => $job->zipcode,
            'salary' => $job->salary,
            'company_name' => $job->company_name,
            'company_description' => $job->company_description,
            'company_website' => $job->company_website,
            'contact_email' => $job->contact_email,
            'contact_phone' => $job->contact_phone,
            'company_logo' => $newJob['company_logo']
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('jobs.index'));

        $this->assertDatabaseHas('job_listings', [
            'title' => $newJob['title']
        ]);
        Storage::disk('public')->assertExists(Job::first()->company_logo);
    }

    public function test_user_can_delete_job(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(route('jobs.destroy', $job));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('jobs.index'));

        $this->assertDatabaseMissing('job_listings', [
            $job->id
        ]);
    }

    public function test_user_cannot_edit_other_users_job(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $job = Job::factory()->create(['user_id' => $user->id]);
        $this->actingAs($anotherUser);

        $response = $this->put(route('jobs.update', $job), [
            'title' => 'Hack Attempt'
        ]);
        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_other_users_job(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $job = Job::factory()->create(['user_id' => $user->id]);
        $this->actingAs($anotherUser);

        $response = $this->delete(route('jobs.destroy', $job));
        $response->assertStatus(403);
    }

    public function test_job_listings_page(): void
    {
        $response = $this->get(route('jobs.index'));
        $response->assertStatus(200);
    }

    public function test_job_listings_create_page(): void
    {
        $response = $this->get(route('jobs.create'));
        $response->assertSessionHasNoErrors();
    }

    public function test_job_listings_show_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('jobs.show', $job));
        $response->assertStatus(200);
    }

    public function test_job_listings_edit_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('jobs.edit', $job));
        $response->assertStatus(200);
    }
}
