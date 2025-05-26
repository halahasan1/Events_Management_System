<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Location;
use App\Models\EventType;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $organizers = User::role('organizer')->get();
        $locations = Location::all();
        $eventTypes = EventType::all();

        foreach (range(1, 5) as $i) {
            Event::create([
                'title' => "Sample Event $i",
                'description' => "This is the description for Event $i.",
                'start_time' => now()->addDays($i),
                'end_time' => now()->addDays($i)->addHours(2),
                'location_id' => $locations->random()->id,
                'event_type_id' => $eventTypes->random()->id,
                'created_by' => $organizers->random()->id,
                'max_capacity' => rand(50, 200),
            ]);
        }
    }
}
