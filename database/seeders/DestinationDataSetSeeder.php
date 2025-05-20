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

        foreach ($data as $row) {
            $record = array_combine($header, $row);

            if (empty($record['Place_Name']) || empty($record['Category'])) {
                continue;
            }

            // Ubah ini untuk tidak menyertakan slug
            $category = Category::firstOrCreate(
                ['name' => trim($record['Category'])]
                // Hapus parameter slug
            );

            // Konversi rating ke skala 0-5
            $rating = min(5.0, floatval($record['Rating']) / 10);

            // Pastikan rating_count dalam batas yang sesuai
            $rating_count = min(9999.99, floatval($record['Rating_Count']));

            try {
                Destination::create([
                    'created_by' => 1, // Ganti dengan ID user sesuai project kamu
                    'category_id' => $category->id,
                    'place_name' => $record['Place_Name'],
                    'slug' => Str::slug($record['Place_Name']),
                    'description' => $record['Description'],
                    'administrative_area' => $record['City'],
                    'province' => '',
                    'rating' => $rating,
                    'rating_count' => $rating_count,
                    'time_minutes' => intval($record['Time_Minutes']),
                    'best_visit_time' => null,
                    'latitude' => floatval($record['Lat']) / 10000000,
                    'longitude' => floatval($record['Long']) / 10000000,
                ]);

                $this->command->info("Added: {$record['Place_Name']}");
            } catch (QueryException $e) {
                $this->command->error("Error adding {$record['Place_Name']}: {$e->getMessage()}");

                // Jika masih error, coba dengan nilai default
                try {
                    Destination::create([
                        'created_by' => 1,
                        'category_id' => $category->id,
                        'place_name' => $record['Place_Name'],
                        'slug' => Str::slug($record['Place_Name']),
                        'description' => $record['Description'],
                        'administrative_area' => $record['City'],
                        'province' => '',
                        'rating' => 4.50, // Nilai default yang aman
                        'rating_count' => 100, // Nilai default yang aman
                        'time_minutes' => intval($record['Time_Minutes']),
                        'best_visit_time' => null,
                        'latitude' => floatval($record['Lat']) / 10000000,
                        'longitude' => floatval($record['Long']) / 10000000,
                    ]);

                    $this->command->info("Added with default rating: {$record['Place_Name']}");
                } catch (QueryException $e2) {
                    $this->command->error("Failed to add {$record['Place_Name']} with default values");
                }
            }
        }
    }
}
