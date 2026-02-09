<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shift::updateOrCreate(
            ['name' => 'Day Shift'],
            [
                'session_1_start' => '08:00:00', // 8 AM
                'session_1_end' => '12:00:00', // 12 PM
                'session_2_start' => '13:00:00', // 1 PM
                'session_2_end' => '17:00:00', // 5 PM
            ]
        );

        Shift::updateOrCreate(
            ['name' => 'Night Shift'],
            [
                'session_1_start' => '20:00:00', // 8 PM
                'session_1_end' => '00:00:00', // Midnight
                'session_2_start' => '01:00:00', // 1 AM
                'session_2_end' => '05:00:00', // 5 AM
            ]
        );
    }
}
