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
                'total_questions' => 15,
                'passing_questions' => 10,
                'passing_percentage' => 66.67,
                'material_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_title' => 'Advanced Laravel',
                'assessment_description' => 'Covers advanced topics like queues, events, broadcasting',
                'assessment_level' => 'Advanced',
                'total_questions' => 40,
                'passing_questions' => 28,
                'passing_percentage' => 70.00,
                'material_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_title' => 'JavaScript Fundamentals',
                'assessment_description' => 'Covers ES6 basics, functions, and control flow',
                'assessment_level' => 'Beginner',
                'total_questions' => 25,
                'passing_questions' => 15,
                'passing_percentage' => 60.00,
                'material_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_title' => 'ReactJS Basics',
                'assessment_description' => 'Test your knowledge of React components and hooks',
                'assessment_level' => 'Intermediate',
                'total_questions' => 35,
                'passing_questions' => 21,
                'passing_percentage' => 60.00,
                'material_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_title' => 'Database & SQL',
                'assessment_description' => 'Covers queries, joins, indexing, and normalization',
                'assessment_level' => 'Intermediate',
                'total_questions' => 30,
                'passing_questions' => 21,
                'passing_percentage' => 70.00,
                'material_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_title' => 'API Development with Laravel',
                'assessment_description' => 'RESTful APIs, Resource Controllers, Sanctum',
                'assessment_level' => 'Advanced',
                'total_questions' => 30,
                'passing_questions' => 24,
                'passing_percentage' => 80.00,
                'material_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => 1,
                'assessment_title' => 'HTML & CSS Essentials',
                'assessment_description' => 'Test basic understanding of web design and styling',
                'assessment_level' => 'Beginner',
                'total_questions' => 20,
                'passing_questions' => 14,
                'passing_percentage' => 70.00,
                'material_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
