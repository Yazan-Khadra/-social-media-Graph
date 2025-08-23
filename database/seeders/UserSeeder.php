<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'yazn102010@email.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'info' => [
                    'name' => 'Yazan Khadra',
                    'profile_image_url' => '/storage/admin_images/yazan.png',
                ]
            ],
            [
                'email' => 'hassan_shabban@email.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'info' => [
                    'name' => 'Hassan Shabban',
                    'profile_image_url' => '/storage/admin_images/hasan.png',
                ]
            ],
            [
                'email' => 'batool_hassan@email.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'info' => [
                    'name' => 'Batool Hassan',
                    'profile_image_url' => '/storage/admin_images/batool.png',
                ]
            ],
            [
                'email' => 'anisa_alyakoub@email.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'info' => [
                    'name' => 'Anisa Alyakoub',
                    'profile_image_url' => '/storage/admin_images/anisa.png',
                ]
            ],
        ];

        foreach ($users as $userData) {
            // إنشاء المستخدم أولًا
            $user = User::create([
                'email' => $userData['email'],
                'password' => $userData['password'],
                'role' => $userData['role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // إنشاء سجل Admin مرتبط بالمستخدم
            Admin::create([
                'user_id' => $user->id,
                'name' => $userData['info']['name'],
                'profile_image_url' => $userData['info']['profile_image_url'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
