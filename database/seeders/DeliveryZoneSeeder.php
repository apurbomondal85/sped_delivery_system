<?php

namespace Database\Seeders;

use App\Models\DeliveryZone;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DeliveryZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryZone::create([
            'restaurant_id' => 1,
            'type' => 'radius',
            'name' => 'Mirpur Radius Zone',
            'center_lat' => 23.7985,
            'center_lng' => 90.3536,
            'radius_meters' => 3000,
            'min_lat' => 23.7685,
            'max_lat' => 23.8285,
            'min_lng' => 90.3236,
            'max_lng' => 90.3836,
        ]);
    }
}
