<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reaction;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reactions = [
            'love',
            'support', 
            'laugh',
            'useless'
        ];

        foreach ($reactions as $reaction) {
            Reaction::create([
                'reaction' => $reaction,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
