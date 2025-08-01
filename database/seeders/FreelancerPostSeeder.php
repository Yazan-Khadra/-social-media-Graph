<?php

namespace Database\Seeders;

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
            'title' => 'Senior Frontend Developer',
            'description' => 'We are looking for an experienced Frontend Developer to join our team. Must have strong skills in React, Vue.js, and modern JavaScript.',
            'status' => 'active',
            'company_id' => 1,
            'skill_id' => 1,
            'work_places' => [2], // Remote work
            'job_types' => [1] // fulltime
        ]);

        FreelancerPost::create([
            'title' => 'Python Backend Developer',
            'description' => 'Join our backend team to develop scalable APIs and microservices using Python, Django, and PostgreSQL.',
                'status' => 'active',
                'company_id' => 1,
            'skill_id' => 2,
            'work_places' => [3], // Hybrid Work
            'job_types' => [1] // fulltime
        ]);

        FreelancerPost::create([
            'title' => 'Mobile App Developer Intern',
            'description' => 'Great opportunity for students to learn mobile development with React Native and Flutter.',
            'status' => 'active',
            'company_id' => 2,
            'skill_id' => 3,
            'work_places' => [1], // on_site
            'job_types' => [3] // internship
        ]);

        FreelancerPost::create([
            'title' => 'Part-time UI/UX Designer',
            'description' => 'Creative designer needed for part-time work on various design projects including web and mobile interfaces.',
                'status' => 'active',
                'company_id' => 2,
            'skill_id' => 4,
            'work_places' => [2], // Remote work
            'job_types' => [2] // part time
        ]);

        FreelancerPost::create([
            'title' => 'DevOps Engineer',
            'description' => 'Experienced DevOps engineer needed to manage our cloud infrastructure and CI/CD pipelines.',
            'status' => 'inactive',
                'company_id' => 3,
            'skill_id' => 5,
            'work_places' => [3], // Hybrid Work
            'job_types' => [1] // fulltime
        ]);
    }
}
