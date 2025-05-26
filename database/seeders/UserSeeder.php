<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Hala Hasan',
            'email' => 'hala.hasan@gmail.com',
            'password' => Hash::make('password123')
        ]);
        $admin->assignRole('admin');

        $organizer = User::create([
            'name' => 'Eminem',
            'email' => 'eminem@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $organizer->assignRole('organizer');

        $reservationManager = User::create([
            'name' => 'Luca Modric',
            'email' => 'luca.modric@gmail.com',
            'password' => Hash::make('password123')
        ]);
        $reservationManager->assignRole('reservation manager');

        $user1 = User::create([
            'name' => 'Sergio Ramos',
            'email' => 'sergio.ramos@gmail.com',
            'password' => Hash::make('password123')
        ]);
        $user1->assignRole('user');

        $user2 = User::create([
            'name' => 'Messi',
            'email' => 'messi@gmail.com',
            'password' => Hash::make('password123')
        ]);
        $user2->assignRole('user');
    }
}
