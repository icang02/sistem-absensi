<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\WaktuAbsen;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'level' => 'admin',
            'alamat' => 'Kelurahan Talia, Kecamatan Abeli',
            'password' => bcrypt('123'),
        ]);
        User::create([
            'nama' => 'Ilmi Faizan',
            'email' => 'ilmifaizan1112@gmail.com',
            'level' => 'user',
            'alamat' => 'Kelurahan Talia, Kecamatan Abeli',
            'password' => bcrypt('123'),
        ]);
        User::create([
            'nama' => 'Imam Saputra',
            'email' => 'imam@gmail.com',
            'level' => 'user',
            'alamat' => 'Ponre Waru, Kolaka',
            'password' => bcrypt('123'),
        ]);

        WaktuAbsen::create([
            'mulai' => '08:00',
            'selesai' => '08:15',
        ]);
    }
}
