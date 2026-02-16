<?php

namespace Database\Seeders;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([ShiftSeeder::class]);

        $nightShiftId = Shift::where('name', 'Night Shift')->value('id');

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'intern@example.com'],
            [
                'name' => 'Intern User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'intern',
                'shift_id' => $nightShiftId,
            ]
        );

        $this->call([UserSeeder::class]);
        $this->call([WeeklyReportsSeeder::class]);
        $this->call([TestDtrLogsSeeder::class]);
        $this->call([WorkCategorySeeder::class]);
    }
}
