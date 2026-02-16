<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkCategory;

class WorkCategorySeeder extends Seeder
{
    public function run(): void
    {
        $predefined = ['Coding', 'Designing', 'SEO'];

        foreach ($predefined as $category) {
            WorkCategory::firstOrCreate([
                'name' => $category,
            ]);
        }
    }
}

