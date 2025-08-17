<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example user data - replace with your own data
      $users = [
    [
        // User table fields
        'email' => 'yazn102010@email.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'info' => [
            'name' => 'Yazan Khadra',
            'profile_image_url' => '',
            'user_id' => 1
        ]
    ],
    [
        // User table fields
        'email' => 'hassan_shabban@email.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
         'info' => [
            'name' => 'Hassan Shabban',
            'profile_image_url' => '',
            'user_id' => 2
        ]
    ],
    [
        // User table fields
        'email' => 'batool_hassan@email.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
         'info' => [
            'name' => 'Batool Hassan',
            'profile_image_url' => '',
            'user_id' => 3
        ]
    ],
     [
        // User table fields
        'email' => 'anisa_alyakoub@email.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
         'info' => [
            'name' => 'Anisa alyakoub',
            'profile_image_url' => '',
            'user_id' => 4
        ]
    ],
];


        foreach ($users as $userData) {
            // Create user first
            $user = User::create([
                'email' => $userData['email'],
                'password' => $userData['password'],
                'role' => $userData['role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            
        }
    }
}
