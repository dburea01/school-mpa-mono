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
        $this->call([
            GroupSeeder::class,
            UserSeeder::class,
            PeriodSeeder::class,
            ClassroomSeeder::class,
            AssignmentSeeder::class,
            WorkSeeder::class,
            ResultSeeder::class,
        ]);
    }
}
