<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DestinationImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            // Pantai Kuta
            [
                'destination_id' => 1,
                'url' => 'destinations/kuta_1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'destination_id' => 1,
                'url' => 'destinations/kuta_2.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Gunung Bromo
            [
                'destination_id' => 2,
                'url' => 'destinations/bromo_1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'destination_id' => 2,
                'url' => 'destinations/bromo_2.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Candi Borobudur
            [
                'destination_id' => 3,
                'url' => 'destinations/borobudur_1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Air Terjun Tumpak Sewu
            [
                'destination_id' => 4,
                'url' => 'destinations/tumpaksewu_1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Dufan
            [
                'destination_id' => 5,
                'url' => 'destinations/dufan_1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('destination_images')->insert($images);
    }
}
