<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentQuestionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            'What is Laravel?',
            'What is a migration in Laravel?',
            'What is an Eloquent ORM?',
            'How do you define a route in Laravel?',
            'What is middleware in Laravel?',
            'What is a service provider in Laravel?',
            'What is the use of `artisan` command?',
            'What is a seeder in Laravel?',
            'What are Laravel facades?',
            'How does Laravel’s Blade templating engine work?',
            'What is the purpose of the `.env` file?',
            'How can you validate a request in Laravel?',
            'What are relationships in Eloquent?',
            'What is CSRF protection in Laravel?',
            'How do you handle file uploads in Laravel?',

            'What is the use of queues in Laravel?',
            'How can you create a controller in Laravel?',
            'What is the difference between `hasOne` and `belongsTo`?',
            'What is route model binding?',
            'What are Laravel policies used for?',
            'What is the difference between `pluck()` and `get()`?',
            'What is the use of `with()` in Eloquent?',
            'How to use pagination in Laravel?',
            'What is the use of jobs and workers in Laravel?',
            'How to send email in Laravel?',
            'What are events and listeners in Laravel?',
            'What is Laravel Sanctum?',
            'What is Laravel Passport?',
            'How do you implement authentication in Laravel?',
            'What is the difference between `web.php` and `api.php` routes?'
        ];

        $now = now();
        $data = [];

        foreach ($questions as $index => $question) {
            $data[] = [
                'trainer_id' => 1,
                'assessment_id' => $index < 15 ? 1 : 2, // First 15 -> Laravel Basics, Next 15 -> Advanced Laravel
                'questions_title' => $question,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('assessment_questions')->insert($data);
    }
}
