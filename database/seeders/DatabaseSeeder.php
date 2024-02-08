<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(4)->create();
        // \App\Models\Profile::factory(100)->create();
        // \App\Models\Trip::factory(4)->create();
        // \App\Models\Review::factory(4)->create();
        // \App\Models\Rating::factory(5)->create();
        // \App\Models\Image::factory(4)->create();
    }
}
