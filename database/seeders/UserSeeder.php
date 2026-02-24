<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str; // ✅ ADDED: Missing import for Str::uuid()

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $password = Hash::make('password123');

        $this->command->info('VIP User create ho raha hai...');

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Create User
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name'           => 'Rehan Bhai',
            'email'          => 'admin@example.com',
            'password'       => $password,
            'plan'           => 3,
            'storage_used'   => 0,
            'storage_limit'  => 1073741824, // 1GB
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ Folder Hierarchy (Correct Parent + Path System)
        |--------------------------------------------------------------------------
        */

        // 🔹 Root Folder (parent_id = null)
        $root = Folder::create([
            'name'      => 'Main Root Folder',
            'user_id'   => $user->id,
            'parent_id' => null,
            'path'      => '/', // temporary
        ]);

        $root->update([
            'path' => '/' . $root->id,
        ]);

        // 🔹 Level 2 Folder
        $folder2 = Folder::create([
            'name'      => 'Inner Folder 1',
            'user_id'   => $user->id,
            'parent_id' => $root->id,
        ]);

        $folder2->update([
            'path' => $root->path . '/' . $folder2->id,
        ]);

        // 🔹 Level 3 Folder
        $folder3 = Folder::create([
            'name'      => 'Deepest Folder',
            'user_id'   => $user->id,
            'parent_id' => $folder2->id,
        ]);

        $folder3->update([
            'path' => $folder2->path . '/' . $folder3->id,
        ]);

        $this->command->info('Nested folders create ho gaye...');

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ Files Creation + Storage Calculation
        |--------------------------------------------------------------------------
        */

        $totalStorageUsed = 0;

        // 🔹 Root Level File
        $size1 = $faker->numberBetween(1024, 500000);

        File::create([
            'user_id'      => $user->id,
            'folder_id'    => null,
            'name'         => $faker->word() . '.txt',
            'path'         => 'user_files/' . $user->id . '/' ,
            'mime_type'    => 'text/plain',
            'size'         => $size1,
            // ✅ REMOVED: is_public and public_token from here!
        ]);

        $totalStorageUsed += $size1;

        // 🔹 Deep Folder Files
        for ($k = 0; $k < 2; $k++) {

            $size = $faker->numberBetween(1024, 500000);

            File::create([
                'user_id'      => $user->id,
                'folder_id'    => $folder3->id,
                'name'         => $faker->word() . '.txt',
                'path'         => 'user_files/' . $user->id . '/' . Str::uuid() . '.txt',
                'mime_type'    => 'text/plain',
                'size'         => $size,
            ]);

            $totalStorageUsed += $size;
        }

        /*
        |--------------------------------------------------------------------------
        | 4️⃣ Update User Storage Used
        |--------------------------------------------------------------------------
        */

        $user->update([
            'storage_used' => $totalStorageUsed
        ]);

        $this->command->info('Mubarak ho ustad! Production-level seeded drive ready!');
    }
}