<?php

namespace Database\Seeders;

use App\Models\User;
 use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Http\Controllers\SkillController;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    $this->call([
        SkillSeeder::class,
        YearSeeder::class,
        MajorSeeder::class,
        UserSeeder::class,
        ProjectSeeder::class,
        JobTypeSeeder::class,
        WorkPlaceSeeder::class,
        ReactionSeeder::class
        
    ]);
}}
