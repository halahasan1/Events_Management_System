<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventType;

class EventTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Workshop',
            'Seminar',
            'Concert',
            'Webinar',
            'Conference',
        ];

        foreach ($types as $type) {
            EventType::firstOrCreate(['name' => $type]);
        }
    }
}
