<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Period::factory()->current()->create([
            'name' => 'Année scolaire 2024/2025',
            'start_date' => '01/09/2024',
            'end_date' => '30/06/2025'
        ]);

        Period::factory()->create([
            'name' => 'Année scolaire 2025/2026',
            'start_date' => '01/09/2025',
            'end_date' => '30/06/2026'
        ]);
    }
}
