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
        Year::insert([['Year_name'=>'First year' ],
                      ['Year_name'=>'second year'],
                      ['Year_name'=>'third year' ],
                      ['Year_name'=>'fourth year'],
                      ['Year_name'=>'fifth year' ]]);
    }
}
