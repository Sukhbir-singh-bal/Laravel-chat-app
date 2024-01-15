<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MessageModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10), // Assuming you have users with IDs from 1 to 10
            'content' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
            'message_to' => $this->faker->numberBetween(1, 10), // Assuming 'message_to' should also be a user ID
            'HasSeen' => $this->faker->boolean,
            'Seen_at' => $this->faker->dateTimeThisMonth(),

        ];
    }
}
