<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ItinerarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itineraries = [
            [
                'user_id' => 2, // Budi
                'title' => 'Liburan Bali 3 Hari',
                'startDate' => '2025-05-01',
                'endDate' => '2025-05-03',
                'status' => 'ongoing',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3, // Siti
                'title' => 'Wisata Jawa Timur',
                'startDate' => '2025-06-15',
                'endDate' => '2025-06-20',
                'status' => 'ongoing',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 4, // Ahmad
                'title' => 'Trip Yogyakarta',
                'startDate' => '2024-12-10',
                'endDate' => '2024-12-15',
                'status' => 'complete',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('itineraries')->insert($itineraries);
    }
}
