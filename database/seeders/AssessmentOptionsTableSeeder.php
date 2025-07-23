<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentOptionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $optionsData = [];

        $optionSets = [
            [
                'Laravel is...',
                ['A framework', true],
                ['A database', false],
                ['A programming language', false],
                ['An operating system', false],
            ],
            [
                'Migration is...',
                ['A Laravel feature to style pages', false],
                ['A tool to manage database schema', true],
                ['A command-line interface', false],
                ['A blade engine helper', false],
            ],
            [
                'Eloquent ORM in Laravel is...',
                ['A database driver', false],
                ['A templating engine', false],
                ['An ActiveRecord implementation', true],
                ['A routing method', false],
            ],
            [
                'Define route using...',
                ['Route::get()', true],
                ['DB::table()', false],
                ['Schema::create()', false],
                ['Blade::render()', false],
            ],
            [
                'Middleware in Laravel is used for...',
                ['Styling pages', false],
                ['Validating requests', true],
                ['Managing database', false],
                ['Writing tests', false],
            ],
            [
                'Service providers are used to...',
                ['Define routes', false],
                ['Load and bind classes into the service container', true],
                ['Create tables', false],
                ['Run migrations', false],
            ],
            [
                'Artisan is...',
                ['A Laravel GUI tool', false],
                ['A command-line interface', true],
                ['A debugging tool', false],
                ['An ORM', false],
            ],
            [
                'Seeder in Laravel is used to...',
                ['Define templates', false],
                ['Add dummy data to database', true],
                ['Run migrations', false],
                ['Validate forms', false],
            ],
            [
                'Facades in Laravel provide...',
                ['A static interface to classes', true],
                ['Database schema', false],
                ['URL routing', false],
                ['View files', false],
            ],
            [
                'Blade is...',
                ['A file format', false],
                ['A templating engine', true],
                ['A routing method', false],
                ['A type of migration', false],
            ],
            [
                '.env file contains...',
                ['Environment variables', true],
                ['HTML code', false],
                ['Controllers', false],
                ['Seeder data', false],
            ],
            [
                'To validate form data, you use...',
                ['Validator::make()', true],
                ['Route::validate()', false],
                ['Blade::check()', false],
                ['Model::save()', false],
            ],
            [
                'Eloquent relationships include...',
                ['hasOne, belongsTo', true],
                ['start, stop', false],
                ['link, unlink', false],
                ['get, set', false],
            ],
            [
                'CSRF protection helps prevent...',
                ['XSS attacks', false],
                ['Cross-site request forgery', true],
                ['Spam emails', false],
                ['Brute-force login', false],
            ],
            [
                'To upload files in Laravel...',
                ['Use move() method on request file', true],
                ['Use DB::insert()', false],
                ['Use Route::post()', false],
                ['Use Schema::upload()', false],
            ],
            [
                'Queues are used for...',
                ['Real-time chat', false],
                ['Email sending in background', true],
                ['File storage', false],
                ['Routing requests', false],
            ],
            [
                'To create a controller...',
                ['php artisan make:controller', true],
                ['php make:controller', false],
                ['php artisan generate:controller', false],
                ['php build:controller', false],
            ],
            [
                'hasOne vs belongsTo difference...',
                ['Direction of the relationship', true],
                ['Both are same', false],
                ['Used for pagination', false],
                ['Used in views', false],
            ],
            [
                'Route model binding is...',
                ['Passing model via URL automatically', true],
                ['Securing routes', false],
                ['Styling route files', false],
                ['Uploading files', false],
            ],
            [
                'Laravel policies are for...',
                ['View templates', false],
                ['Authorization logic', true],
                ['Schema design', false],
                ['Database seeding', false],
            ],
            [
                'pluck() returns...',
                ['Single column values', true],
                ['All rows', false],
                ['HTML output', false],
                ['Errors', false],
            ],
            [
                'with() in Eloquent...',
                ['Eager loads relationships', true],
                ['Validates data', false],
                ['Creates migrations', false],
                ['Saves models', false],
            ],
            [
                'Pagination in Laravel is done using...',
                ['paginate()', true],
                ['get()', false],
                ['sort()', false],
                ['route()', false],
            ],
            [
                'Jobs in Laravel handle...',
                ['Long running tasks', true],
                ['Form validations', false],
                ['Blade rendering', false],
                ['Database queries', false],
            ],
            [
                'To send email...',
                ['Mail::to()->send()', true],
                ['Route::mail()', false],
                ['Email::send()', false],
                ['Mail::create()', false],
            ],
            [
                'Events and listeners allow...',
                ['Handling background tasks', false],
                ['Decoupled communication between parts', true],
                ['Routing logic', false],
                ['Pagination', false],
            ],
            [
                'Laravel Sanctum is for...',
                ['Simple API token auth', true],
                ['UI rendering', false],
                ['Database backups', false],
                ['Job queues', false],
            ],
            [
                'Laravel Passport is used for...',
                ['OAuth2 authentication', true],
                ['Migration rollback', false],
                ['UI theming', false],
                ['Form requests', false],
            ],
            [
                'Authentication is implemented via...',
                ['Auth scaffolding', true],
                ['Route::auth()', false],
                ['DB::auth()', false],
                ['Model::login()', false],
            ],
            [
                'web.php vs api.php...',
                ['web.php includes session and csrf, api.php does not', true],
                ['Both are same', false],
                ['Only for admin', false],
                ['Used only in CLI', false],
            ]
        ];

        $questionId = 1;

        foreach ($optionSets as $set) {
            foreach (array_slice($set, 1) as $option) {
                $optionsData[] = [
                    'trainer_id' => 1,
                    'assessment_id' => 1,
                    'question_id' => $questionId,
                    'options' => $option[0],
                    'correct_option' => $option[1],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $questionId++;
        }

        DB::table('assessment_options')->insert($optionsData);
    }
}
