<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        // Day Shift: 8am-5pm (Break 12pm-1pm)
        Shift::updateOrCreate(
            ['name' => 'Day Shift'],
            [
                'start_time'  => '08:00:00',
                'end_time'    => '17:00:00',
                'break_start' => '12:00:00',
                'break_end'   => '13:00:00',
            ]
        );

        // Graveyard Shift: 4am-1pm (Break 7am-8am)
        Shift::updateOrCreate(
            ['name' => 'Graveyard Shift'],
            [
                'start_time'  => '04:00:00',
                'end_time'    => '13:00:00',
                'break_start' => '07:00:00',
                'break_end'   => '08:00:00',
            ]
        );

        // Mid Shift: 1pm-10pm (Break 6pm-7pm)
        Shift::updateOrCreate(
            ['name' => 'Mid Shift'],
            [
                'start_time'  => '13:00:00',
                'end_time'    => '22:00:00',
                'break_start' => '18:00:00',
                'break_end'   => '19:00:00',
            ]
        );
    }
}
