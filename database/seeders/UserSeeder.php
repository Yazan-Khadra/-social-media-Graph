<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Year;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $users = [
        //     [
        //         'first_name' => 'Obada',
        //         'last_name' => 'Rawas',
        //         'gender' => 'male',
        //         'birth_date' => '1995-01-15',
        //         'bio' => 'Senior full-stack developer with expertise in web development.',
        //         'role' => 'student',
        //         'year_id' => 4,
        //         'major_id' => 1,
        //         'user_id' => 1,
        //     ],
        //     [
        //         'first_name' => 'Sarah',
        //         'last_name' => 'moathen',
        //         'gender' => 'female',
        //         'birth_date' => '1998-03-22',
        //         'email' => 'sarah@gmail.com',
        //         'mobile_number' => '+963-934-555-124',
        //         'password' => Hash::make('sarah123'),
        //         'bio' => 'Creative UI/UX designer specialized in user-centered design.',
        //         'role' => 'student',
        //         'year_id' => 3,
        //         'major_id' => 2,
        //         'user_id' => 2,
        //     ],
        //     [
        //         'first_name' => 'Michael',
        //         'last_name' => 'Chen',
        //         'gender' => 'male',
        //         'birth_date' => '1997-07-10',
        //         'email' => 'michael.c@gmail.com',
        //         'mobile_number' => '+963-934-555-125',
        //         'password' => Hash::make('micheal123'),
        //         'bio' => 'Backend developer specializing in Python and microservices.',
        //         'role' => 'student',
        //         'year_id' => 5,
        //         'major_id' => 2,
        //         'user_id' => 3,
        //     ],
        //     [
        //         'first_name' => 'Emily',
        //         'last_name' => 'Davis',
        //         'gender' => 'female',
        //         'birth_date' => '1996-11-30',
        //         'email' => 'emily.d@devmate.com',
        //         'mobile_number' => '+963-934-555-126',
        //         'password' => Hash::make('password123'),
        //         'bio' => 'Certified Project Manager with agile methodology expertise.',
        //         'role' => 'student',
        //         'year_id' => 4,
        //         'major_id' => 3,
        //         'user_id' => 4,
        //     ],
        //     [
        //         'first_name' => 'David',
        //         'last_name' => 'Wilson',
        //         'gender' => 'male',
        //         'birth_date' => '1999-04-15',
        //         'email' => 'david.w@devmate.com',
        //         'mobile_number' => '+963-934-555-127',
        //         'password' => Hash::make('password123'),
        //         'bio' => 'QA specialist with strong automation testing background.',
        //         'role' => 'student',
        //         'year_id' => 2,
        //         'major_id' => 1,
        //         'user_id' => 5,
        //     ],
        // ];

        // foreach ($users as $userData) {
        //     $userData['created_at'] = now();
        //     $userData['updated_at'] = now();
        //     Student::create($userData);
        // }
    
}
}

