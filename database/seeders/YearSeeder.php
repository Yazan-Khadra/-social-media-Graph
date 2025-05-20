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
            ['id' => '1', 'Year_name' => 'First Year'],
            ['id' => '2', 'Year_name' => 'Second Year'],
            ['id' => '3', 'Year_name' => 'Third Year'],
            ['id' => '4', 'Year_name' => 'Fourth Year'],
            ['id' => '5', 'Year_name' => 'Fifth Year']
        ]);
    }
}
