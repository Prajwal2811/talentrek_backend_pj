<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentQuestionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('assessment_questions')->insert([
            [
                'trainer_id' => 1,
                'assessment_id' => 1,
                'questions_title' => 'What is Laravel?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_id' => 1,
                'questions_title' => 'What is a migration in Laravel?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
