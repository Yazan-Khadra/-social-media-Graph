<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // Projects for years 1-3 (no major required)
        Project::create([
            'name' => 'project 1',
            'description' => 'Introduction to HTML, CSS, and JavaScript',
            'year_id' => 3
        ]);

        Project::create([
            'name' => 'Scientific calculations',
            'description' => 'Introduction to SQL and database design',
            'year_id' => 3
        ]);

        Project::create([
            'name' => 'data base 1',
            'description' => 'Learning OOP concepts with Java',
            'year_id' => 3
        ]);

        Project::create([
            'name' => 'Algorithms and Data Structures 1',
            'description' => 'Implementation of common data structures',
            'year_id' => 2
        ]);

        Project::create([
            'name' => 'Alogrithms and Data structures 2',
            'description' => 'Software development lifecycle and methodologies',
            'year_id' => 3
        ]);

        // Projects for years 4-5 (with major required)
        Project::create([
            'name' => 'Nural Networks',
            'description' => 'Advanced AI algorithms and implementations',
            'year_id' => 4,
            'major_id' => 2  // Assuming 1 is Computer Science
        ]);

        Project::create([
            'name' => 'Network Security',
            'description' => 'Advanced network security protocols and implementations',
            'year_id' => 4,
            'major_id' => 3  // Assuming 2 is Network Engineering
        ]);

        Project::create([
            'name' => 'Mobile App Development',
            'description' => 'Advanced mobile application development',
            'year_id' => 3,
            
        ]);

        Project::create([
            'name' => 'programing 3',
            'description' => 'Cloud infrastructure and services',
            'year_id' => 2,
            
        ]);

        Project::create([
            'name' => 'Introdction to artificial Intelegince',
            'description' => 'Processing and analyzing large datasets',
            'year_id' => 3,
            
        ]);
    }
}
