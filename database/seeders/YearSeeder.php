<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Year;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Year::insert([
            ['id' => '1', 'year_name' => 'First Year'],
            ['id' => '2', 'year_name' => 'Second Year'],
            ['id' => '3', 'year_name' => 'Third Year'],
            ['id' => '4', 'year_name' => 'Fourth Year'],
            ['id' => '5', 'year_name' => 'Fifth Year']
        ]);
    }
}
