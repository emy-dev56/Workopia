<?php

namespace Tests\Feature\Job;

use App\Models\Applicant;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_apply_to_job(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create(['user_id' => $user->id]);

        $emp = User::factory()->create();
        $this->actingAs($emp);
        $application = [
            'full_name' => fake()->name(),
            'contact_phone' => fake()->phoneNumber(),
            'contact_email' => fake()->email(),
            'message' => fake()->paragraph(),
            'location' => fake()->city(),
            'resume' => UploadedFile::fake()->create('resume.pdf',500,'application/pdf'),
        ];
        $response = $this->post(route('applicant.store', $job), $application);
        $response->assertSessionHasNoErrors();
        
        $this->assertDatabaseHas('applicants', [
            'full_name' => $application['full_name'],
            'user_id' => $emp->id,
            'job_id' => $job->id
        ]);       
    }

    public function test_user_can_delete_application(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);
        $job = Job::factory()->create(['user_id' => $user->id]);

        $emp = User::factory()->create();
        $this->actingAs($emp);
        $application = Applicant::factory()->create(['user_id' => $emp->id, 'job_id' => $job->id]);
        
        $this->actingAs($user);
        $response = $this->delete(route('applicant.destroy', $application));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('applicants', [
            'id' => $application->id
        ]);
    }
}
