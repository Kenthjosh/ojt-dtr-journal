<?php

namespace Database\Factories;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DtrLog>
 */
class DtrLogFactory extends Factory
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
            'shift_id' => Shift::factory(),
            'type' => $this->faker->randomElement([1, 2]),
            'recorded_at' => now(),
            'work_date' => now()->format('Y-m-d'),
            'late_minutes' => 0,
            'work_minutes' => 0,
        ];
    }
}
