<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            [
                'name' => 'Jerwin Noval',
                'email' => 'jerwin@example.com',
                'password' => Hash::make('password'),
                'shift_id' => 2 //Night Shift
            ],
            [
                'name' => 'Christy Asis',
                'email' => 'christy@example.com',
                'password' => Hash::make('password'),
                'shift_id' => 1 //Day Shift
            ],
            [
                'name' => 'Mary Grace Rosell',
                'email' => 'mary@example.com',
                'password' => Hash::make('password'),
                'shift_id' => 1 //Day Shift
            ],
            [
                'name' => 'John Mhell',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'shift_id' => 2 //Night Shift
            ],
            [
                'name' => 'Melvin Aike',
                'email' => 'melvin@example.com',
                'password' => Hash::make('password'),
                'shift_id' => 2 //Night Shift
            ],
            [
                'name' => 'Ron Michael',
                'email' => 'ron@example.com',
                'password' => Hash::make('password'),
                'shift_id' => 2 //Night Shift
            ],
            [
                'name' => 'Nin Kyle',
                'email' => 'nin@example.com',
                'password' => Hash::make('password'),
                'shift_id' => 2 //Night Shift
            ],
            [
                'name' => 'Roselyn Arenas',
                'email' => 'roselyn@example.com',
                'password' => Hash::make('password'),
                'shift_id' => 1 //Day Shift

            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
