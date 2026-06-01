<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'salary' => fake()->numberBetween(1000, 10000),
            'tags' => implode(', ', fake()->words(3)),
            'job_type' => fake()->randomElement(['Full-Time', 'Part-time', 'Contract', 'Temporary', 'Internship', 'Volunteer', 'On-Call']),
            'remote' => fake()->boolean(),
            'requirements' => fake()->paragraphs(3, true),
            'benefits' => fake()->paragraphs(2, true),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zipcode' => fake()->postcode(),
            'company_name' => fake()->company(),
            'company_description' => fake()->paragraphs(2, true),
            'company_logo' => fake()->imageUrl(100, 100, 'business'),
            'company_website' => fake()->url(),
            'contact_email' => fake()->safeEmail(),
            'contact_phone' => fake()->phoneNumber(),
        ];
    }
}
