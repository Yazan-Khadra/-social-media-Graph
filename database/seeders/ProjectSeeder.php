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
            'name' => 'Basic Web Development',
            'description' => 'Introduction to HTML, CSS, and JavaScript',
            'year_id' => 1
        ]);

        Project::create([
            'name' => 'Database Fundamentals',
            'description' => 'Introduction to SQL and database design',
            'year_id' => 1
        ]);

        Project::create([
            'name' => 'Object-Oriented Programming',
            'description' => 'Learning OOP concepts with Java',
            'year_id' => 2
        ]);

        Project::create([
            'name' => 'Data Structures',
            'description' => 'Implementation of common data structures',
            'year_id' => 2
        ]);

        Project::create([
            'name' => 'Software Engineering',
            'description' => 'Software development lifecycle and methodologies',
            'year_id' => 3
        ]);

        // Projects for years 4-5 (with major required)
        Project::create([
            'name' => 'AI and Machine Learning',
            'description' => 'Advanced AI algorithms and implementations',
            'year_id' => 4,
            'major_id' => 1  // Assuming 1 is Computer Science
        ]);

        Project::create([
            'name' => 'Network Security',
            'description' => 'Advanced network security protocols and implementations',
            'year_id' => 4,
            'major_id' => 2  // Assuming 2 is Network Engineering
        ]);

        Project::create([
            'name' => 'Mobile App Development',
            'description' => 'Advanced mobile application development',
            'year_id' => 5,
            'major_id' => 1  // Computer Science
        ]);

        Project::create([
            'name' => 'Cloud Computing',
            'description' => 'Cloud infrastructure and services',
            'year_id' => 5,
            'major_id' => 2  // Network Engineering
        ]);

        Project::create([
            'name' => 'Big Data Analytics',
            'description' => 'Processing and analyzing large datasets',
            'year_id' => 5,
            'major_id' => 1  // Computer Science
        ]);
    }
}
