<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class DestinationDataSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('app/Data/destinasi-wisata-indonesia.csv');

        if (!file_exists($path)) {
            dd("File tidak ditemukan di path: " . $path);
        }

        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', $rows[0]);
        $data = array_slice($rows, 1);

        // Definisi batas koordinat untuk wilayah Jawa Timur (Surabaya, Gresik, Malang, dan sekitarnya)
        // Area ini mencakup: Surabaya, Gresik, Sidoarjo, Mojokerto, Malang, Batu, Pasuruan, dll
        $eastJavaBounds = [
            'lat_min' => -8.3000,  // Batas selatan (sampai Malang Selatan)
            'lat_max' => -7.0000,  // Batas utara (sampai Gresik/Lamongan)
            'lng_min' => 112.2000, // Batas barat (Mojokerto/Jombang)
            'lng_max' => 113.0000  // Batas timur (Pasuruan/Probolinggo)
        ];

        $addedCount = 0;
        $skippedCount = 0;

        foreach ($data as $row) {
            $record = array_combine($header, $row);

            if (empty($record['Place_Name']) || empty($record['Category'])) {
                $skippedCount++;
                continue;
            }

            // Konversi koordinat dari format dataset
            $latitude = floatval($record['Lat']) / 10000000;
            $longitude = floatval($record['Long']) / 10000000;

            // Cek apakah koordinat berada dalam area Jawa Timur (Surabaya, Malang, Gresik, dll)
            $isInEastJava = $this->isWithinBounds($latitude, $longitude, $eastJavaBounds);

            if (!$isInEastJava) {
                $skippedCount++;
                continue; // Skip destinasi yang tidak berada di wilayah Jawa Timur yang ditentukan
            }

            // Tentukan kota berdasarkan koordinat (lebih detail)
            $city = $this->determineCityByCoordinates($latitude, $longitude);

            // Buat kategori jika belum ada
            $category = Category::firstOrCreate([
                'name' => trim($record['Category'])
            ]);

            // Konversi dan batasi nilai rating agar sesuai dengan schema (max 9.99)
            $rawRating = floatval($record['Rating']);
            $rating = min(9.99, $rawRating / 10); // Misal dari skala 0–100 -> jadi 0–10
            if ($rating < 0) $rating = 0;

            // Batasi rating_count sesuai kapasitas
            $rawCount = floatval($record['Rating_Count']);
            $rating_count = min(999999, $rawCount);
            if ($rating_count < 0) $rating_count = 0;

            try {
                Destination::create([
                    'created_by' => 1,
                    'category_id' => $category->id,
                    'place_name' => $record['Place_Name'],
                    'slug' => Str::slug($record['Place_Name']),
                    'description' => $record['Description'],
                    'administrative_area' => $city, // Gunakan kota yang sudah ditentukan
                    'province' => 'Jawa Timur',
                    'rating' => $rating,
                    'rating_count' => $rating_count,
                    'time_minutes' => intval($record['Time_Minutes']),
                    'best_visit_time' => null,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);

                $this->command->info("Added ({$city}): {$record['Place_Name']} - Lat: {$latitude}, Lng: {$longitude}");
                $addedCount++;
            } catch (QueryException $e) {
                $this->command->error("Error adding {$record['Place_Name']}: {$e->getMessage()}");

                // Coba lagi dengan nilai default yang aman
                try {
                    Destination::create([
                        'created_by' => 1,
                        'category_id' => $category->id,
                        'place_name' => $record['Place_Name'],
                        'slug' => Str::slug($record['Place_Name']),
                        'description' => $record['Description'],
                        'administrative_area' => $city,
                        'province' => 'Jawa Timur',
                        'rating' => 4.5, // default aman
                        'rating_count' => 100,
                        'time_minutes' => intval($record['Time_Minutes']),
                        'best_visit_time' => null,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ]);

                    $this->command->info("Added with default rating ({$city}): {$record['Place_Name']}");
                    $addedCount++;
                } catch (QueryException $e2) {
                    $this->command->error("Failed to add {$record['Place_Name']} with default values");
                    $skippedCount++;
                }
            }
        }

        $this->command->info("=== SEEDING COMPLETED ===");
        $this->command->info("Total destinations added: {$addedCount}");
        $this->command->info("Total destinations skipped: {$skippedCount}");
    }

    /**
     * Cek apakah koordinat berada dalam batas area tertentu
     */
    private function isWithinBounds($latitude, $longitude, $bounds)
    {
        return $latitude >= $bounds['lat_min'] &&
            $latitude <= $bounds['lat_max'] &&
            $longitude >= $bounds['lng_min'] &&
            $longitude <= $bounds['lng_max'];
    }

    /**
     * Tentukan nama kota berdasarkan koordinat yang lebih spesifik
     */
    private function determineCityByCoordinates($latitude, $longitude)
    {
        $cities = [
            'Surabaya' => ['lat_min' => -7.35, 'lat_max' => -7.15, 'lng_min' => 112.6, 'lng_max' => 112.85],
            'Gresik' => ['lat_min' => -7.25, 'lat_max' => -7.05, 'lng_min' => 112.5, 'lng_max' => 112.7],
            'Malang' => ['lat_min' => -8.2, 'lat_max' => -7.8, 'lng_min' => 112.5, 'lng_max' => 112.7],
            'Batu' => ['lat_min' => -7.9, 'lat_max' => -7.8, 'lng_min' => 112.5, 'lng_max' => 112.55],
            'Sidoarjo' => ['lat_min' => -7.5, 'lat_max' => -7.3, 'lng_min' => 112.65, 'lng_max' => 112.8],
            'Mojokerto' => ['lat_min' => -7.55, 'lat_max' => -7.4, 'lng_min' => 112.4, 'lng_max' => 112.6],
            'Pasuruan' => ['lat_min' => -7.8, 'lat_max' => -7.6, 'lng_min' => 112.8, 'lng_max' => 113.0],
        ];

        foreach ($cities as $city => $bounds) {
            if (
                $latitude >= $bounds['lat_min'] && $latitude <= $bounds['lat_max'] &&
                $longitude >= $bounds['lng_min'] && $longitude <= $bounds['lng_max']
            ) {
                return $city;
            }
        }

        return 'Jawa Timur';
    }
}
