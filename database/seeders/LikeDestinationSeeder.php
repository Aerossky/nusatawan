<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LikeDestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $likedDestinations = [
            [
                'user_id' => 2, // Budi
                'destination_id' => 1, // Pantai Kuta
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2, // Budi
                'destination_id' => 3, // Candi Borobudur
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3, // Siti
                'destination_id' => 2, // Gunung Bromo
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3, // Siti
                'destination_id' => 4, // Air Terjun Tumpak Sewu
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 4, // Ahmad
                'destination_id' => 2, // Gunung Bromo
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('liked_destinations')->insert($likedDestinations);
    }
}
