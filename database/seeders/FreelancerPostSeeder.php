<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FreelancerPost;

class FreelancerPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FreelancerPost::create([
            [
                'title' => 'React Developer Needed',
                'description' => 'Looking for a React developer to build a dashboard.',
                'status' => 'active',
                'company_id' => 1,
            ],
            [
                'title' => 'Backend API Engineer',
                'description' => 'Develop RESTful APIs using Laravel.',
                'status' => 'active',
                'company_id' => 2,
            ],
            [
                'title' => 'UI/UX Designer',
                'description' => 'Design modern interfaces for mobile apps.',
                'status' => 'closed',
                'company_id' => 3,
            ],
        ]);
    }
}
