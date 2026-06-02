<?php

namespace Tests\Unit;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobModelTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_job_belongs_to_user(): void
    {
        $job = Job::factory()->create();
        $this->assertInstanceOf(User::class, $job->user);
    }
}
