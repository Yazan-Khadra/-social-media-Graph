<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Major;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Major::insert([['Major_name'=>'programming' ],
                       ['Major_name'=>'AI'],
                       ['Major_name'=>'Network' ]]);
    }
}
