<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Skill::insert([
           // ['name' => 'Laravel', 'logo_url' => asset('skills/laravel.svg')],
            ['name' => 'C++', 'logo_url' => asset('public\skills\c++.svg')],
            ['name' => 'C#', 'logo_url' => asset('public\skills\c-sharp-logo.svg')],
            ['name' => 'Python', 'logo_url' => asset('public\skills\python.svg')],
            ['name' => 'React', 'logo_url' => asset('public\skills\react-native.svg')],
            ['name' => 'Flutter', 'logo_url' => asset('public\skills\flutter.svg')],
            ['name' => 'Nodejs', 'logo_url' => asset('public\skills\nodejs.svg')],
            ['name' => 'Java script', 'logo_url' => asset('public\skills\javascript.svg')],
            ['name' => 'Java', 'logo_url' => asset('public\skills\java.svg')],
            ['name' => 'MySQL', 'logo_url' => asset('public\skills\mysql-logo.svg')],
            ['name' => 'GitHub', 'logo_url' =>asset('public\skills\github.svg')],
            ['name' => 'Git', 'logo_url' => asset('public\skills\git.svg')],
            ['name' => 'Html', 'logo_url' => asset('public\skills\html-5.svg')],
            ['name' => 'IntelliJ', 'logo_url' => asset('public\skills\intellij-idea.svg')],
            ['name' => 'Ruby', 'logo_url' => asset('public\skills\ruby.svg')],
           // ['name' => 'PHP', 'logo_url' => asset('skills/laravel.svg')],
           // ['name' => 'Css', 'logo_url' => asset('skills/laravel.svg')]
        ]);
    }
}
//C:\xampp\htdocs\DevMate\DevMate\public\skills\flutter.svg
