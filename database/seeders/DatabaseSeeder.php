<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'email' => 'alharbisa24@gmail.com',
            'password' => bcrypt('1'),
        ]);

    }
}
