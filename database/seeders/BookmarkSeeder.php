<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'test@test.com')->firstOrFail();

        $jobIds = Job::all()->pluck('id')->toArray();

        $randomJobs = array_rand($jobIds, 3);

        foreach($randomJobs as $jobId) {
            $user->bookmarkedJobs()->attach($jobId);
        }

    }
}
