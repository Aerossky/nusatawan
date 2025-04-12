<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Database\Factories\DestinationFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinations = [
            [
                'created_by' => 1, // Admin
                'category_id' => 1, // Pantai
                'place_name' => 'Pantai Kuta',
                'slug' => 'pantai-kuta',
                'description' => 'Pantai yang terkenal di Bali dengan pemandangan sunset yang indah dan aktivitas surfing yang populer.',
                'city' => 'Bali',
                'rating' => 4.5,
                'rating_count' => 120,
                'time_minutes' => 180,
                'latitude' => -8.7184,
                'longitude' => 115.1686,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 1, // Admin
                'category_id' => 2, // Gunung
                'place_name' => 'Gunung Bromo',
                'slug' => 'gunung-bromo',
                'description' => 'Gunung berapi aktif yang terkenal dengan pemandangan matahari terbit yang spektakuler dan lautan pasirnya.',
                'city' => 'Jawa Timur',
                'rating' => 4.8,
                'rating_count' => 95,
                'time_minutes' => 240,
                'latitude' => -7.9425,
                'longitude' => 112.9530,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 2, // Budi
                'category_id' => 5, // Budaya
                'place_name' => 'Candi Borobudur',
                'slug' => 'candi-borobudur',
                'description' => 'Candi Buddha terbesar di dunia yang dibangun pada abad ke-9 dan merupakan situs warisan dunia UNESCO.',
                'city' => 'Magelang',
                'rating' => 4.7,
                'rating_count' => 150,
                'time_minutes' => 120,
                'latitude' => -7.6079,
                'longitude' => 110.2038,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 3, // Siti
                'category_id' => 3, // Air Terjun
                'place_name' => 'Air Terjun Tumpak Sewu',
                'slug' => 'air-terjun-tumpak-sewu',
                'description' => 'Air terjun spektakuler dengan pemandangan mirip air terjun di film Jurassic Park.',
                'city' => 'Lumajang',
                'rating' => 4.6,
                'rating_count' => 85,
                'time_minutes' => 150,
                'latitude' => -8.2296,
                'longitude' => 112.9168,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 4, // Ahmad
                'category_id' => 4, // Taman Hiburan
                'place_name' => 'Dufan',
                'slug' => 'dufan',
                'description' => 'Taman hiburan terbesar di Jakarta dengan berbagai wahana menarik untuk semua usia.',
                'city' => 'Jakarta',
                'rating' => 4.3,
                'rating_count' => 200,
                'time_minutes' => 300,
                'latitude' => -6.1253,
                'longitude' => 106.8338,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('destinations')->insert($destinations);
    }
}
