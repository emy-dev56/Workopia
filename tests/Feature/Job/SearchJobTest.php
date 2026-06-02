<?php

namespace Tests\Feature\Job;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchJobTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_search_jobs_by_keyword(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create(['user_id' => $user->id, 'title' => 'Search Test']);

        $response = $this->get(route('jobs.search', ['keyword' => 'Search']));
        $response->assertSessionHasNoErrors();
        $response->assertSee($job->title);

        $response = $this->get(route('jobs.search', ['keyword' => 'fffddd']));
        $response->assertSessionHasNoErrors();
        $response->assertDontSee($job->title);
    }

    public function test_search_jobs_by_location(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create(['user_id' => $user->id, 'city' => 'Austin']);

        $response = $this->get(route('jobs.search', ['location' => 'Austin']));
        $response->assertSessionHasNoErrors();
        $response->assertSee($job->city);

        $response = $this->get(route('jobs.search', ['location' => 'fffddd']));
        $response->assertSessionHasNoErrors();
        $response->assertDontSee($job->city);
    }
}
