<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeeklyReports>
 */
class WeeklyReportsFactory extends Factory
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
            'week_start' => $this->faker->unique()->date(),
            'week_end' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'viewed', 'certified']),
            'submitted_at' => $this->faker->dateTime(),
            'viewed_at' => $this->faker->dateTime(),
            'certified_at' => $this->faker->dateTime(),
            'certified_by' => optional(User::where('role', 'admin')->first())->id,
            'signature' => $this->faker->imageUrl(),
            'entries' => json_encode([
                'week_focus' => $this->faker->sentence(),
                'topics_learned' => [
                    $this->faker->sentence(),
                    $this->faker->sentence(),
                ],
                'outputs_links' => [
                    [
                        'url' => $this->faker->url(),
                        'description' => $this->faker->sentence(),
                    ],
                ],
                'what_built' => $this->faker->paragraph(),
                'decisions_reasoning' => [
                    'decision_1' => $this->faker->sentence(),
                    'decision_2' => $this->faker->sentence(),
                ],
                'challenges_blockers' => $this->faker->paragraph(),
                'improve_next_time' => [
                    'improvement_1' => $this->faker->sentence(),
                    'improvement_2' => $this->faker->sentence(),
                ],
                'key_takeaway' => $this->faker->sentence(),
            ]),
        ];
    }
}
