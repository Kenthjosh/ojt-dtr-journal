<?php

namespace Database\Seeders;

use App\Models\Attendances;
use Illuminate\Database\Seeder;

class AttendancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attendances::factory()->count(10)->create();
    }
}
