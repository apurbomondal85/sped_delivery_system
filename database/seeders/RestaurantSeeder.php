<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('type', 'admin')->first();

        if ($admin) {
            Restaurant::create([
                'user_id' => $admin->id,
                'name'    => 'Sped Delivery Restaurant',
                'address' => 'Dhaka, Bangladesh',
                'lat'     => 23.810331,
                'lng'     => 90.412521,
            ]);
        }
    }
}
