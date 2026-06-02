<?php

namespace Tests\Feature\Job;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookmarkTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_bookmark_job(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create();
        $response = $this->from(route('jobs.show', $job))->post(route('bookmarks.store', $job));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
        $response->assertRedirect(route('jobs.show', $job));
        $this->assertDatabaseHas('job_user_bookmarks', [
            'job_id' => $job->id,
            'user_id' => $user->id
        ]);
    }

    /**
     * A basic feature test example.
     */
    public function test_user_cannot_bookmark_job_twice(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create();
        $user->bookmarkedJobs()->attach($job->id);

        $response = $this->from(route('jobs.show', $job))->post(route('bookmarks.store', $job));
        $response->assertSessionHas([
            'error' => 'Job already bookmarked'
        ]);
        $response->assertRedirect(route('jobs.show', $job));
    }

    /**
     * A basic feature test example.
     */
    public function test_user_can_delete_bookmark(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create();
        $user->bookmarkedJobs()->attach($job->id);
        $response = $this->from(route('jobs.show', $job))->delete(route('bookmarks.destroy', $job));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('jobs.show', $job));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('job_user_bookmarks', [
            'job_id' => $job->id,
            'user_id' => $user->id
        ]);
    }


    /**
     * A basic feature test example.
     */
    public function test_user_cannot_delete_unbookmark(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create();
        $response = $this->from(route('jobs.show', $job))->delete(route('bookmarks.destroy', $job));
        $response->assertSessionHas('error');
        $response->assertRedirect(route('jobs.show', $job));
    }

    /**
     * A basic feature test example.
     */
    public function test_my_bookmarks_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create();
        $user->bookmarkedJobs()->attach($job->id);

        $response = $this->get(route('bookmarks.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSee($job->title);

    }
}
