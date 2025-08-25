<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
                'type' => 'admin',
            ]
        );

        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => $faker->unique()->safeEmail()],
                [
                    'name' => $faker->name(),
                    'type' => 'customer',
                    'lat'  => $faker->latitude(23.79, 23.82),
                    'lng'  => $faker->longitude(90.34, 90.38),
                ]
            );
        }

        for ($i = 1; $i <= 3; $i++) {
            User::updateOrCreate(
                ['email' => $faker->unique()->safeEmail()],
                [
                    'name' => $faker->name(),
                    'type' => 'delivery_man',
                    'lat'  => $faker->latitude(23.79, 23.82),
                    'lng'  => $faker->longitude(90.34, 90.38),
                ]
            );
        }
    }
}
