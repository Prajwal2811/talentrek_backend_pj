<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reviews')->insert([
            [
                'jobseeker_id' => 1,
                'user_type' => 'trainer',
                'user_id' => 1,
                'reviews' => 'Very knowledgeable and supportive trainer.',
                'ratings' => 5,
                'trainer_material' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jobseeker_id' => 2,
                'user_type' => 'mentor',
                'user_id' => 2,
                'reviews' => 'Guided me really well throughout the program.',
                'ratings' => 4,
                'trainer_material' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jobseeker_id' => 3,
                'user_type' => 'coach',
                'user_id' => 3,
                'reviews' => 'Helped me build confidence and prepare for interviews.',
                'ratings' => 4,
                'trainer_material' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jobseeker_id' => 4,
                'user_type' => 'assessor',
                'user_id' => 4,
                'reviews' => 'Fair and objective evaluation.',
                'ratings' => 5,
                'trainer_material' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
