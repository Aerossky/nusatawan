<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'user_id' => 2, // Budi
                'destination_id' => 1, // Pantai Kuta
                'rating' => 5,
                'comment' => 'Pantai yang indah dengan sunset yang memukau. Sangat ramai tapi tetap menyenangkan.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3, // Siti
                'destination_id' => 1, // Pantai Kuta
                'rating' => 4,
                'comment' => 'Bagus untuk surfing dan berenang. Agak kotor di beberapa area.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 4, // Ahmad
                'destination_id' => 2, // Gunung Bromo
                'rating' => 5,
                'comment' => 'Pemandangan yang luar biasa! Sunrise di Bromo tidak boleh dilewatkan.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2, // Budi
                'destination_id' => 3, // Candi Borobudur
                'rating' => 5,
                'comment' => 'Warisan budaya yang menakjubkan. Membawa kita kembali ke masa kejayaan Kerajaan.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3, // Siti
                'destination_id' => 4, // Air Terjun Tumpak Sewu
                'rating' => 4,
                'comment' => 'Air terjun yang sangat indah dan megah. Akses agak sulit tapi worth it.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('reviews')->insert($reviews);
    }
}
