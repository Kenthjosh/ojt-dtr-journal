<?php

namespace Database\Seeders;

use App\Models\WeeklyReports;
use Illuminate\Database\Seeder;

class WeeklyReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WeeklyReports::factory()->count(5)->create();
    }
}
