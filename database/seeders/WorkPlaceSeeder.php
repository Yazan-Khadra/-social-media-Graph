<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkPlace;

class WorkPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkPlace::create(['name' => 'on_site']);
        WorkPlace::create(['name' => 'Remote work']);
        WorkPlace::create(['name' => 'Hybrid Work']);
    }
}
