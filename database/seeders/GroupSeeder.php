<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\User;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::create([
            'group_name' => 'A Group',
            'admin_id' => 1,
            'year_id' => 1
        ]);

        Group::create([
            'group_name' => 'B Squad',
            'admin_id' => 2,
            'year_id' => 1
        ]);

        Group::create([
            'group_name' => 'C Team',
            'admin_id' => 3,
            'year_id' => 2
        ]);

        Group::create([
            'group_name' => 'D Team',
            'admin_id' => 4,
            'year_id' => 2
        ]);

        Group::create([
            'group_name' => 'E Team',
            'admin_id' => 5,
            'year_id' => 3
        ]);

        Group::create([
            'group_name' => 'Z Team',
            'admin_id' => 6,
            'year_id' => 3
        ]);

        Group::create([
            'group_name' => 'F Team',
            'admin_id' => 7,
            'year_id' => 4
        ]);
    }
}
