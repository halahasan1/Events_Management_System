<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'City Hall', 'address' => '123 Main St', 'city' => 'New York', 'country' => 'USA'],
            ['name' => 'Conference Center', 'address' => '456 State Ave', 'city' => 'Chicago', 'country' => 'USA'],
            ['name' => 'Beachside Resort', 'address' => '789 Ocean Blvd', 'city' => 'Los Angeles', 'country' => 'USA'],
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate($location);
        }
    }
}
