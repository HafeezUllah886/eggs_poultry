<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\auctions;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(branchesSeeder::class);
       $this->call(accountsSeeder::class);
       $this->call(userSeeder::class);
       $this->call(expenseCategorySeeder::class);

    }
}
