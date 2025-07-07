<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentOptionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('assessment_options')->insert([
            // Question 1 options
            [
                'trainer_id' => 1,
                'assessment_id' => 1,
                'question_id' => 1,
                'options' => 'A framework',
                'correct_option' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_id' => 1,
                'question_id' => 1,
                'options' => 'A database',
                'correct_option' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_id' => 1,
                'question_id' => 1,
                'options' => 'A programming language',
                'correct_option' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_id' => 1,
                'question_id' => 1,
                'options' => 'An operating system',
                'correct_option' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Question 2 options
            [
                'trainer_id' => 1,
                'assessment_id' => 1,
                'question_id' => 2,
                'options' => 'A Laravel feature to style pages',
                'correct_option' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_id' => 1,
                'question_id' => 2,
                'options' => 'A tool to manage database schema',
                'correct_option' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_id' => 1,
                'question_id' => 2,
                'options' => 'A command-line interface',
                'correct_option' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_id' => 1,
                'question_id' => 2,
                'options' => 'A blade engine helper',
                'correct_option' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
