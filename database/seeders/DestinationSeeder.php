<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
                'created_by' => 1,
                'category_id' => 1,
                'place_name' => 'Pantai Kuta',
                'slug' => 'pantai-kuta',
                'description' => 'Pantai yang terkenal di Bali dengan pemandangan sunset yang indah dan aktivitas surfing yang populer.',
                'administrative_area' => 'Kuta',
                'province' => 'Bali',
                'rating' => 4.5,
                'rating_count' => 120,
                'time_minutes' => 180,
                'best_visit_time' => 'sore',
                'latitude' => -8.7184,
                'longitude' => 115.1686,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 1,
                'category_id' => 2,
                'place_name' => 'Gunung Bromo',
                'slug' => 'gunung-bromo',
                'description' => 'Gunung berapi aktif yang terkenal dengan pemandangan matahari terbit yang spektakuler dan lautan pasirnya.',
                'administrative_area' => 'Probolinggo',
                'province' => 'Jawa Timur',
                'rating' => 4.8,
                'rating_count' => 95,
                'time_minutes' => 240,
                'best_visit_time' => 'pagi',
                'latitude' => -7.9425,
                'longitude' => 112.9530,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 2,
                'category_id' => 5,
                'place_name' => 'Candi Borobudur',
                'slug' => 'candi-borobudur',
                'description' => 'Candi Buddha terbesar di dunia yang dibangun pada abad ke-9 dan merupakan situs warisan dunia UNESCO.',
                'administrative_area' => 'Magelang',
                'province' => 'Jawa Tengah',
                'rating' => 4.7,
                'rating_count' => 150,
                'time_minutes' => 120,
                'best_visit_time' => 'pagi',
                'latitude' => -7.6079,
                'longitude' => 110.2038,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 3,
                'category_id' => 3,
                'place_name' => 'Air Terjun Tumpak Sewu',
                'slug' => 'air-terjun-tumpak-sewu',
                'description' => 'Air terjun spektakuler dengan pemandangan mirip air terjun di film Jurassic Park.',
                'administrative_area' => 'Lumajang',
                'province' => 'Jawa Timur',
                'rating' => 4.6,
                'rating_count' => 85,
                'time_minutes' => 150,
                'best_visit_time' => 'siang',
                'latitude' => -8.2296,
                'longitude' => 112.9168,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 4,
                'category_id' => 4,
                'place_name' => 'Dufan',
                'slug' => 'dufan',
                'description' => 'Taman hiburan terbesar di Jakarta dengan berbagai wahana menarik untuk semua usia.',
                'administrative_area' => 'Ancol',
                'province' => 'DKI Jakarta',
                'rating' => 4.3,
                'rating_count' => 200,
                'time_minutes' => 300,
                'best_visit_time' => 'siang',
                'latitude' => -6.1253,
                'longitude' => 106.8338,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('destinations')->insert($destinations);
    }
}
