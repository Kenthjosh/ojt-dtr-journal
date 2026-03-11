<?php

namespace Database\Seeders;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $dayShiftId = Shift::where('name', 'Day Shift')->value('id');
        $midShiftId = Shift::where('name', 'Mid Shift')->value('id');
        $graveyardId = Shift::where('name', 'Graveyard Shift')->value('id');

        $users = [
            [
                'name' => 'Jerwin Noval',
                'email' => 'jerwin@example.com',
                'password' => Hash::make('password'),
                'shift_id' => $dayShiftId,
            ],
            [
                'name' => 'Mid Shift Intern',
                'email' => 'mid@example.com',
                'password' => Hash::make('password'),
                'shift_id' => $midShiftId,
            ],
            [
                'name' => 'Graveyard Intern',
                'email' => 'grave@example.com',
                'password' => Hash::make('password'),
                'shift_id' => $graveyardId,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
