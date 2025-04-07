<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ItineraryDestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itineraryDestinations = [
            // Liburan Bali 3 Hari
            [
                'itinerary_id' => 1,
                'destination_id' => 1, // Pantai Kuta
                'visitDateTime' => '2025-05-01 14:00:00',
                'orderIndex' => 1,
                'note' => 'Bawa sunblock dan topi. Sewa payung di pantai.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Wisata Jawa Timur
            [
                'itinerary_id' => 2,
                'destination_id' => 2, // Gunung Bromo
                'visitDateTime' => '2025-06-16 04:00:00',
                'orderIndex' => 1,
                'note' => 'Berangkat pagi untuk sunrise. Bawa jaket tebal.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'itinerary_id' => 2,
                'destination_id' => 4, // Air Terjun Tumpak Sewu
                'visitDateTime' => '2025-06-17 10:00:00',
                'orderIndex' => 2,
                'note' => 'Bawa baju ganti dan sepatu trekking.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Trip Yogyakarta
            [
                'itinerary_id' => 3,
                'destination_id' => 3, // Candi Borobudur
                'visitDateTime' => '2024-12-11 06:00:00',
                'orderIndex' => 1,
                'note' => 'Datang pagi-pagi untuk menghindari ramai.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('itinerary_destinations')->insert($itineraryDestinations);
    }
}
