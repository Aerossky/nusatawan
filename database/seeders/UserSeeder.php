<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Admin user
        DB::table('users')->insert([
            'name' => 'Admin Wisata',
            'email' => 'admin@wisata.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'isAdmin' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Regular users
        $users = [
            [
                'name' => 'Risky',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'isAdmin' => false,
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'isAdmin' => false,
            ],
            [
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'isAdmin' => false,
            ],
            [
                'name' => 'Dewi Permata',
                'email' => 'dewi@example.com',
                'password' => Hash::make('password123'),
                'status' => 'inactive',
                'isAdmin' => false,
            ],
        ];

        foreach ($users as $user) {
            $user['created_at'] = Carbon::now();
            $user['updated_at'] = Carbon::now();
            DB::table('users')->insert($user);
        }

        // Generate 30 user random dari factory
        // User::factory()->count(30)->create();
    }
}
