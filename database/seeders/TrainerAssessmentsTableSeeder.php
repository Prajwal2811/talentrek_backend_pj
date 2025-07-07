<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainerAssessmentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('trainer_assessments')->insert([
            [
                'trainer_id' => 1,
                'assessment_title' => 'Laravel Basics',
                'assessment_description' => 'Introductory Laravel assessment',
                'assessment_level' => 'Beginner',
                'total_questions' => 2,
                'passing_questions' => 1,
                'passing_percentage' => 50.00,
                'material_id' => 1, // Fixed: was material_id
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
