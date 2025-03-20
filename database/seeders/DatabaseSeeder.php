<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Skills;
use App\Models\Qualifications;
use App\Models\Experiences;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'name' => 'admin',  // Name of the user
            'email' => 'admin@gmail.com',  // Email of the user
            'role' => 'admin',  // Email of the user
            'password' => Hash::make('Test@123'),  // Password, hashed
        ]);
        // Seed Skills
        Skills::create(['skill' => 'PHP']);
        Skills::create(['skill' => 'JavaScript']);
        Skills::create(['skill' => 'Laravel']);
        Skills::create(['skill' => 'Vue.js']);

        // Seed Qualifications
        Qualifications::create(['qualification' => 'Diploma']);
        Qualifications::create(['qualification' => 'Bachelors']);
        Qualifications::create(['qualification' => 'Masters']);
        Qualifications::create(['qualification' => 'PhD']);

        // Seed Experiences
        Experiences::create(['experience' => '1 Year']);
        Experiences::create(['experience' => '2 Years']);
        Experiences::create(['experience' => '3 Years']);
        Experiences::create(['experience' => '5 Years']);
    }
}
