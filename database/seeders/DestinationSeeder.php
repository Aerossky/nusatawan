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
            // SURABAYA DAN SEKITARNYA
            [
                'created_by' => 1,
                'category_id' => 5,
                'place_name' => 'Tugu Pahlawan',
                'slug' => 'tugu-pahlawan',
                'description' => 'Monumen bersejarah yang menjadi simbol perjuangan rakyat Surabaya dalam mempertahankan kemerdekaan Indonesia.',
                'administrative_area' => 'Surabaya Pusat',
                'province' => 'Jawa Timur',
                'rating' => 4.5,
                'rating_count' => 87,
                'time_minutes' => 60,
                'best_visit_time' => 'pagi',
                'latitude' => -7.2459,
                'longitude' => 112.7378,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 1,
                'category_id' => 4,
                'place_name' => 'Surabaya North Quay',
                'slug' => 'surabaya-north-quay',
                'description' => 'Area wisata di Pelabuhan Tanjung Perak dengan pemandangan kapal-kapal dan aktivitas pelabuhan.',
                'administrative_area' => 'Surabaya Utara',
                'province' => 'Jawa Timur',
                'rating' => 4.3,
                'rating_count' => 65,
                'time_minutes' => 120,
                'best_visit_time' => 'sore',
                'latitude' => -7.2012,
                'longitude' => 112.7322,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 2,
                'category_id' => 4,
                'place_name' => 'Kebun Binatang Surabaya',
                'slug' => 'kebun-binatang-surabaya',
                'description' => 'Kebun binatang tertua di Indonesia yang memiliki koleksi satwa yang beragam.',
                'administrative_area' => 'Surabaya Selatan',
                'province' => 'Jawa Timur',
                'rating' => 4.1,
                'rating_count' => 156,
                'time_minutes' => 180,
                'best_visit_time' => 'pagi',
                'latitude' => -7.2964,
                'longitude' => 112.7378,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 2,
                'category_id' => 5,
                'place_name' => 'House of Sampoerna',
                'slug' => 'house-of-sampoerna',
                'description' => 'Museum dan galeri yang menampilkan sejarah pabrik rokok Sampoerna dalam bangunan kolonial Belanda.',
                'administrative_area' => 'Surabaya Utara',
                'province' => 'Jawa Timur',
                'rating' => 4.6,
                'rating_count' => 112,
                'time_minutes' => 90,
                'best_visit_time' => 'siang',
                'latitude' => -7.2315,
                'longitude' => 112.7340,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 3,
                'category_id' => 6,
                'place_name' => 'Taman Bungkul',
                'slug' => 'taman-bungkul',
                'description' => 'Taman kota yang asri dengan berbagai fasilitas rekreasi dan kuliner.',
                'administrative_area' => 'Surabaya Selatan',
                'province' => 'Jawa Timur',
                'rating' => 4.5,
                'rating_count' => 198,
                'time_minutes' => 120,
                'best_visit_time' => 'sore',
                'latitude' => -7.2903,
                'longitude' => 112.7385,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // GRESIK (Berjarak sekitar 20-30 km dari Surabaya)
            [
                'created_by' => 3,
                'category_id' => 5,
                'place_name' => 'Makam Sunan Giri',
                'slug' => 'makam-sunan-giri',
                'description' => 'Kompleks pemakaman salah satu wali songo yang menjadi destinasi wisata religi populer.',
                'administrative_area' => 'Gresik',
                'province' => 'Jawa Timur',
                'rating' => 4.7,
                'rating_count' => 145,
                'time_minutes' => 60,
                'best_visit_time' => 'pagi',
                'latitude' => -7.1612,
                'longitude' => 112.6104,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 4,
                'category_id' => 5,
                'place_name' => 'Pulau Bawean',
                'slug' => 'pulau-bawean',
                'description' => 'Pulau eksotis dengan pantai berpasir putih dan air laut yang jernih.',
                'administrative_area' => 'Gresik',
                'province' => 'Jawa Timur',
                'rating' => 4.8,
                'rating_count' => 87,
                'time_minutes' => 480,
                'best_visit_time' => 'siang',
                'latitude' => -5.8206,
                'longitude' => 112.6473,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // SIDOARJO (Berjarak sekitar 20-25 km dari Surabaya)
            [
                'created_by' => 1,
                'category_id' => 3,
                'place_name' => 'Lumpur Lapindo',
                'slug' => 'lumpur-lapindo',
                'description' => 'Situs semburan lumpur panas yang kini menjadi objek wisata sejarah dan edukasi.',
                'administrative_area' => 'Sidoarjo',
                'province' => 'Jawa Timur',
                'rating' => 3.9,
                'rating_count' => 65,
                'time_minutes' => 90,
                'best_visit_time' => 'pagi',
                'latitude' => -7.5307,
                'longitude' => 112.7113,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 2,
                'category_id' => 4,
                'place_name' => 'Delta Fishing',
                'slug' => 'delta-fishing',
                'description' => 'Tempat rekreasi keluarga dengan kolam pemancingan dan berbagai fasilitas hiburan.',
                'administrative_area' => 'Sidoarjo',
                'province' => 'Jawa Timur',
                'rating' => 4.3,
                'rating_count' => 123,
                'time_minutes' => 180,
                'best_visit_time' => 'siang',
                'latitude' => -7.4531,
                'longitude' => 112.7159,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // PASURUAN (Berjarak sekitar 40-50 km dari Surabaya)
            [
                'created_by' => 3,
                'category_id' => 2,
                'place_name' => 'Gunung Bromo',
                'slug' => 'gunung-bromo',
                'description' => 'Gunung berapi aktif yang terkenal dengan pemandangan matahari terbit yang spektakuler dan lautan pasirnya.',
                'administrative_area' => 'Probolinggo',
                'province' => 'Jawa Timur',
                'rating' => 4.8,
                'rating_count' => 245,
                'time_minutes' => 240,
                'best_visit_time' => 'pagi',
                'latitude' => -7.9425,
                'longitude' => 112.9530,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 4,
                'category_id' => 2,
                'place_name' => 'Kebun Raya Purwodadi',
                'slug' => 'kebun-raya-purwodadi',
                'description' => 'Kebun raya yang dikhususkan untuk konservasi tumbuhan kering dan semi kering.',
                'administrative_area' => 'Pasuruan',
                'province' => 'Jawa Timur',
                'rating' => 4.5,
                'rating_count' => 98,
                'time_minutes' => 150,
                'best_visit_time' => 'pagi',
                'latitude' => -7.8038,
                'longitude' => 112.7456,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // MALANG (Berjarak sekitar 90-100 km dari Surabaya)
            [
                'created_by' => 1,
                'category_id' => 3,
                'place_name' => 'Air Terjun Coban Rondo',
                'slug' => 'air-terjun-coban-rondo',
                'description' => 'Air terjun populer dengan pemandangan alam yang asri dan udara yang sejuk.',
                'administrative_area' => 'Malang',
                'province' => 'Jawa Timur',
                'rating' => 4.6,
                'rating_count' => 176,
                'time_minutes' => 120,
                'best_visit_time' => 'siang',
                'latitude' => -7.8849,
                'longitude' => 112.4776,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 2,
                'category_id' => 1,
                'place_name' => 'Pantai Balekambang',
                'slug' => 'pantai-balekambang',
                'description' => 'Pantai dengan pura mirip Tanah Lot Bali yang menawarkan pemandangan matahari terbit yang menakjubkan.',
                'administrative_area' => 'Malang',
                'province' => 'Jawa Timur',
                'rating' => 4.7,
                'rating_count' => 132,
                'time_minutes' => 180,
                'best_visit_time' => 'pagi',
                'latitude' => -8.4031,
                'longitude' => 112.5369,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // LAMONGAN (Berjarak sekitar 40-50 km dari Surabaya)
            [
                'created_by' => 3,
                'category_id' => 4,
                'place_name' => 'WBL (Wisata Bahari Lamongan)',
                'slug' => 'wisata-bahari-lamongan',
                'description' => 'Taman rekreasi keluarga dengan wahana air dan berbagai atraksi menarik.',
                'administrative_area' => 'Lamongan',
                'province' => 'Jawa Timur',
                'rating' => 4.4,
                'rating_count' => 154,
                'time_minutes' => 240,
                'best_visit_time' => 'siang',
                'latitude' => -6.8721,
                'longitude' => 112.3278,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 4,
                'category_id' => 5,
                'place_name' => 'Makam Sunan Drajat',
                'slug' => 'makam-sunan-drajat',
                'description' => 'Situs wisata religi yang merupakan makam salah satu wali songo penyebar agama Islam di Jawa.',
                'administrative_area' => 'Lamongan',
                'province' => 'Jawa Timur',
                'rating' => 4.6,
                'rating_count' => 89,
                'time_minutes' => 60,
                'best_visit_time' => 'pagi',
                'latitude' => -6.8893,
                'longitude' => 112.1715,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // MOJOKERTO (Berjarak sekitar 40-50 km dari Surabaya)
            [
                'created_by' => 1,
                'category_id' => 3,
                'place_name' => 'Air Terjun Dlundung',
                'slug' => 'air-terjun-dlundung',
                'description' => 'Air terjun yang berada di kawasan Taman Hutan Raya R. Soerjo dengan pemandangan yang asri.',
                'administrative_area' => 'Mojokerto',
                'province' => 'Jawa Timur',
                'rating' => 4.5,
                'rating_count' => 87,
                'time_minutes' => 120,
                'best_visit_time' => 'siang',
                'latitude' => -7.6361,
                'longitude' => 112.5266,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 2,
                'category_id' => 5,
                'place_name' => 'Candi Tikus',
                'slug' => 'candi-tikus',
                'description' => 'Situs arkeologi peninggalan Kerajaan Majapahit berupa petirtaan (pemandian) kuno.',
                'administrative_area' => 'Trowulan',
                'province' => 'Jawa Timur',
                'rating' => 4.4,
                'rating_count' => 76,
                'time_minutes' => 60,
                'best_visit_time' => 'pagi',
                'latitude' => -7.5571,
                'longitude' => 112.3818,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // BOJONEGORO (Berjarak sekitar 80-100 km dari Surabaya)
            [
                'created_by' => 3,
                'category_id' => 3,
                'place_name' => 'Waduk Pacal',
                'slug' => 'waduk-pacal',
                'description' => 'Waduk yang dikelilingi perbukitan hijau dengan pemandangan alam yang menakjubkan.',
                'administrative_area' => 'Bojonegoro',
                'province' => 'Jawa Timur',
                'rating' => 4.3,
                'rating_count' => 65,
                'time_minutes' => 120,
                'best_visit_time' => 'sore',
                'latitude' => -7.2835,
                'longitude' => 111.7761,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 4,
                'category_id' => 6,
                'place_name' => 'Kayangan Api',
                'slug' => 'kayangan-api',
                'description' => 'Fenomena alam berupa api abadi yang keluar dari dalam tanah, terletak di kawasan hutan jati.',
                'administrative_area' => 'Bojonegoro',
                'province' => 'Jawa Timur',
                'rating' => 4.4,
                'rating_count' => 89,
                'time_minutes' => 60,
                'best_visit_time' => 'sore',
                'latitude' => -7.2429,
                'longitude' => 111.7336,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // TUBAN (Berjarak sekitar 100-120 km dari Surabaya)
            [
                'created_by' => 1,
                'category_id' => 1,
                'place_name' => 'Pantai Boom',
                'slug' => 'pantai-boom',
                'description' => 'Pantai yang terletak di pusat kota Tuban dengan pemandangan sunset yang indah.',
                'administrative_area' => 'Tuban',
                'province' => 'Jawa Timur',
                'rating' => 4.2,
                'rating_count' => 78,
                'time_minutes' => 120,
                'best_visit_time' => 'sore',
                'latitude' => -6.8991,
                'longitude' => 112.0647,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 2,
                'category_id' => 5,
                'place_name' => 'Goa Akbar',
                'slug' => 'goa-akbar',
                'description' => 'Goa alam dengan stalaktit dan stalakmit yang menakjubkan serta kolam air di dalamnya.',
                'administrative_area' => 'Tuban',
                'province' => 'Jawa Timur',
                'rating' => 4.4,
                'rating_count' => 65,
                'time_minutes' => 90,
                'best_visit_time' => 'siang',
                'latitude' => -6.8870,
                'longitude' => 111.9504,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // JOMBANG (Berjarak sekitar 70-80 km dari Surabaya)
            [
                'created_by' => 3,
                'category_id' => 5,
                'place_name' => 'Makam Gus Dur',
                'slug' => 'makam-gus-dur',
                'description' => 'Kompleks pemakaman KH. Abdurrahman Wahid (Gus Dur) yang menjadi destinasi wisata religi populer.',
                'administrative_area' => 'Jombang',
                'province' => 'Jawa Timur',
                'rating' => 4.7,
                'rating_count' => 187,
                'time_minutes' => 60,
                'best_visit_time' => 'pagi',
                'latitude' => -7.6163,
                'longitude' => 112.2491,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // BATU (Berjarak sekitar 90-100 km dari Surabaya)
            [
                'created_by' => 4,
                'category_id' => 4,
                'place_name' => 'Jatim Park 1',
                'slug' => 'jatim-park-1',
                'description' => 'Taman rekreasi keluarga dengan berbagai wahana permainan dan edukasi.',
                'administrative_area' => 'Batu',
                'province' => 'Jawa Timur',
                'rating' => 4.6,
                'rating_count' => 213,
                'time_minutes' => 240,
                'best_visit_time' => 'siang',
                'latitude' => -7.8897,
                'longitude' => 112.5294,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 1,
                'category_id' => 4,
                'place_name' => 'Museum Angkut',
                'slug' => 'museum-angkut',
                'description' => 'Museum transportasi terbesar di Asia Tenggara dengan koleksi kendaraan dari berbagai era.',
                'administrative_area' => 'Batu',
                'province' => 'Jawa Timur',
                'rating' => 4.7,
                'rating_count' => 198,
                'time_minutes' => 180,
                'best_visit_time' => 'siang',
                'latitude' => -7.8789,
                'longitude' => 112.5175,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'created_by' => 2,
                'category_id' => 2,
                'place_name' => 'Coban Talun',
                'slug' => 'coban-talun',
                'description' => 'Air terjun dengan tinggi sekitar 75 meter yang dikelilingi hutan pinus yang sejuk.',
                'administrative_area' => 'Batu',
                'province' => 'Jawa Timur',
                'rating' => 4.5,
                'rating_count' => 87,
                'time_minutes' => 120,
                'best_visit_time' => 'pagi',
                'latitude' => -7.7921,
                'longitude' => 112.5007,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // DESTINASI LUAR JAWA TIMUR (DILUAR RADIUS 50KM DARI SURABAYA)

            // SEMARANG
            [
                'created_by' => 3,
                'category_id' => 5,
                'place_name' => 'Lawang Sewu',
                'slug' => 'lawang-sewu',
                'description' => 'Bangunan bersejarah peninggalan Belanda yang terkenal dengan arsitektur uniknya.',
                'administrative_area' => 'Semarang',
                'province' => 'Jawa Tengah',
                'rating' => 4.6,
                'rating_count' => 176,
                'time_minutes' => 90,
                'best_visit_time' => 'pagi',
                'latitude' => -6.9840,
                'longitude' => 110.4107,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // JOGJA
            [
                'created_by' => 4,
                'category_id' => 5,
                'place_name' => 'Candi Prambanan',
                'slug' => 'candi-prambanan',
                'description' => 'Candi Hindu terbesar di Indonesia yang termasuk dalam situs warisan UNESCO.',
                'administrative_area' => 'Klaten',
                'province' => 'Jawa Tengah',
                'rating' => 4.8,
                'rating_count' => 234,
                'time_minutes' => 120,
                'best_visit_time' => 'sore',
                'latitude' => -7.7520,
                'longitude' => 110.4914,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // JAKARTA
            [
                'created_by' => 1,
                'category_id' => 6,
                'place_name' => 'Monas',
                'slug' => 'monas',
                'description' => 'Monumen Nasional yang menjadi ikon kota Jakarta dengan puncak berlapiskan emas.',
                'administrative_area' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'rating' => 4.6,
                'rating_count' => 287,
                'time_minutes' => 120,
                'best_visit_time' => 'pagi',
                'latitude' => -6.1754,
                'longitude' => 106.8272,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // BALI
            [
                'created_by' => 2,
                'category_id' => 1,
                'place_name' => 'Pantai Kuta',
                'slug' => 'pantai-kuta',
                'description' => 'Pantai yang terkenal di Bali dengan pemandangan sunset yang indah dan aktivitas surfing yang populer.',
                'administrative_area' => 'Kuta',
                'province' => 'Bali',
                'rating' => 4.5,
                'rating_count' => 324,
                'time_minutes' => 180,
                'best_visit_time' => 'sore',
                'latitude' => -8.7184,
                'longitude' => 115.1686,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // LOMBOK
            [
                'created_by' => 3,
                'category_id' => 1,
                'place_name' => 'Pantai Pink',
                'slug' => 'pantai-pink',
                'description' => 'Pantai dengan pasir berwarna merah muda yang sangat unik dan pemandangan laut yang jernih.',
                'administrative_area' => 'Lombok Timur',
                'province' => 'Nusa Tenggara Barat',
                'rating' => 4.7,
                'rating_count' => 145,
                'time_minutes' => 240,
                'best_visit_time' => 'siang',
                'latitude' => -8.8384,
                'longitude' => 116.5521,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // MEDAN
            [
                'created_by' => 4,
                'category_id' => 3,
                'place_name' => 'Danau Toba',
                'slug' => 'danau-toba',
                'description' => 'Danau vulkanik terbesar di Indonesia dengan pemandangan alam yang spektakuler.',
                'administrative_area' => 'Samosir',
                'province' => 'Sumatera Utara',
                'rating' => 4.8,
                'rating_count' => 276,
                'time_minutes' => 360,
                'best_visit_time' => 'siang',
                'latitude' => 2.6208,
                'longitude' => 98.8675,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // MAKASSAR
            [
                'created_by' => 1,
                'category_id' => 5,
                'place_name' => 'Benteng Rotterdam',
                'slug' => 'benteng-rotterdam',
                'description' => 'Benteng peninggalan kolonial Belanda yang menjadi salah satu ikon kota Makassar.',
                'administrative_area' => 'Makassar',
                'province' => 'Sulawesi Selatan',
                'rating' => 4.5,
                'rating_count' => 132,
                'time_minutes' => 90,
                'best_visit_time' => 'sore',
                'latitude' => -5.1349,
                'longitude' => 119.4068,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('destinations')->insert($destinations);
    }
}
