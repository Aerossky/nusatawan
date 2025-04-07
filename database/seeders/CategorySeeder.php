<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Pantai', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Gunung', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Air Terjun', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Taman Hiburan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Budaya', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Sejarah', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Kuliner', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Alam', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('categories')->insert($categories);
    }
}
