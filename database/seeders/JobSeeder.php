<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobListings = include database_path('seeders/data/job_listings.php');

        $testUserID = User::where('email', 'test@test.com')->value('id');
        $userIDs = User::where('id', '!=', $testUserID)->pluck('id')->toArray();

        foreach ($jobListings as $index => &$jobListing) {
            if ($index < 2) {
                $jobListing['user_id'] = $testUserID;
            } else {
                $jobListing['user_id'] = $userIDs[array_rand($userIDs)];
            }
            $jobListing['created_at'] = now();
            $jobListing['updated_at'] = now();
        }

        DB::table('job_listings')->insert($jobListings);

        echo "Jobs created successfully";
    }
}
