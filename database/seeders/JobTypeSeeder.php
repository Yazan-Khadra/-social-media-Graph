<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobType;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobType::create(['name' => 'fulltime']);
        JobType::create(['name' => 'part time']);
        JobType::create(['name' => 'internship']);
    }
}
