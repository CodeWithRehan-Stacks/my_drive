<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Is purani factory wali line ko hata dein ya comment kar dein:
        // \App\Models\User::factory(10)->create();

        // 2. Yahan apna naya UserSeeder call karein:
        $this->call([
            UserSeeder::class,
        ]);
    }
}