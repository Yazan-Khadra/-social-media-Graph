<?php

namespace Database\Seeders;

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
            ['name'=>'C++','logo_url'=>'skills/c++.svg'],
            ['name'=>'C#','logo_url'=>'skills/c-sharp-logo.svg'],
            ['name'=>'Python','logo_url'=>'skills/python.svg'],
            ['name'=>'React','logo_url'=>'skills/react-native.svg'],
            ['name'=>'Flutter','logo_url'=>'skills/flutter.svg'],
            ['name'=>'Nodejs','logo_url'=>'skills/nodejs.svg'],
            ['name'=>'Javascript','logo_url'=>'skills/javascript.svg'],
            ['name'=>'Java','logo_url'=>'skills/java.svg'],
            ['name'=>'MySQL','logo_url'=>'skills/mysql-logo.svg'],
            ['name'=>'GitHub','logo_url'=>'skills/github.svg'],
            ['name'=>'Git','logo_url'=>'skills/git.svg'],
            ['name'=>'Html','logo_url'=>'skills/html-5.svg'],
            ['name'=>'IntelliJ','logo_url'=>'skills/intellij-idea.svg'],
            ['name'=>'Ruby','logo_url'=>'skills/ruby.svg'],
        ]);
    }
}
