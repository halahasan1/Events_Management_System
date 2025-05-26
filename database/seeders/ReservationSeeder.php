<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Event;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('user')->get();
        $events = Event::all();

        foreach ($events as $event) {
            foreach ($users->random(2) as $user) {
                Reservation::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                ]);
            }
        }
    }
}
