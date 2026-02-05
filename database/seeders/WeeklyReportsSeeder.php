<?php

namespace Database\Seeders;

use App\Models\WeeklyReports;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WeeklyReports;
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
