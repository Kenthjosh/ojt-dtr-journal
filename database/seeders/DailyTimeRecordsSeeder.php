<?php

namespace Database\Seeders;

use App\Models\DailyTimeRecords;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyTimeRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DailyTimeRecords::factory()->count(10)->create();
    }
}
