<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //default day shift
            'name' => 'Day Shift',
            'session_1_start' => '08:00:00',
            'session_1_end'   => '12:00:00',
            'session_2_start' => '13:00:00',
            'session_2_end'   => '17:00:00',
        ];
    }
}
