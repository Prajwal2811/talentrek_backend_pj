<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingMaterialsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('training_materials')->insert([
            [
                'trainer_id' => 1,
                'training_type' => 'online',

                'training_title' => 'Full Stack Web Development',
                'training_sub_title' => 'HTML, CSS, JavaScript, PHP, and Laravel',
                'training_descriptions' => 'Comprehensive course to build dynamic web apps using modern tech stack.',
                'training_category' => 'Technical',
                'training_price' => 799.00,
                'thumbnail_file_path' => 'uploads/thumbnails/fullstack.jpg',
                'thumbnail_file_name' => 'fullstack.jpg',
                'training_objective' => 'Build responsive full-stack apps with backend logic.',
                'session_type' => 'Live',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'trainer_id' => 2,
                'training_type' => 'Offline',
                'training_title' => 'Time Management Mastery',
                'training_sub_title' => 'Boost Productivity and Efficiency',
                'training_descriptions' => 'Hands-on sessions for improving personal and professional time handling.',
                'training_category' => 'Soft Skills',
                'training_price' => 299.00,
                'thumbnail_file_path' => 'uploads/thumbnails/time-mgmt.jpg',
                'thumbnail_file_name' => 'time-mgmt.jpg',
                'training_objective' => 'Learn effective time-blocking and prioritization.',
                'session_type' => 'Pre-recorded',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'trainer_id' => 3,

                'training_type' => 'recorded',

                'training_title' => 'Excel for Business Analytics',
                'training_sub_title' => 'Excel + Pivot Tables + Dashboarding',
                'training_descriptions' => 'Master Excel formulas, data analysis, and reporting.',
                'training_category' => 'Technical',
                'training_price' => 399.50,
                'thumbnail_file_path' => 'uploads/thumbnails/excel.jpg',
                'thumbnail_file_name' => 'excel.jpg',
                'training_objective' => 'Use Excel for financial and business data insights.',
                'session_type' => 'Live',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'trainer_id' => 4,
                'training_type' => 'online',
                'training_title' => 'Design Thinking for Innovation',
                'training_sub_title' => 'Solve Complex Problems Creatively',
                'training_descriptions' => 'Learn the framework to think, prototype, and test ideas.',
                'training_category' => 'Leadership',
                'training_price' => 599.00,
                'thumbnail_file_path' => 'uploads/thumbnails/design-thinking.jpg',
                'thumbnail_file_name' => 'design-thinking.jpg',
                'training_objective' => 'Apply human-centered design to real-world issues.',
                'session_type' => 'Pre-recorded',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'trainer_id' => 1,
                'training_type' => 'Offline',
                'training_title' => 'Communication for Managers',
                'training_sub_title' => 'Lead with Clarity and Confidence',
                'training_descriptions' => 'Develop verbal and non-verbal communication for leadership.',
                'training_category' => 'Soft Skills',
                'training_price' => 449.00,
                'thumbnail_file_path' => 'uploads/thumbnails/communication.jpg',
                'thumbnail_file_name' => 'communication.jpg',
                'training_objective' => 'Improve cross-functional and team communication.',
                'session_type' => 'Live',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'trainer_id' => 2,
                'training_type' => 'online',

                'training_title' => 'Cybersecurity Fundamentals',
                'training_sub_title' => 'Protect Digital Assets',
                'training_descriptions' => 'Introduction to security concepts, tools, and best practices.',
                'training_category' => 'Technical',
                'training_price' => 699.00,
                'thumbnail_file_path' => 'uploads/thumbnails/cybersecurity.jpg',
                'thumbnail_file_name' => 'cybersecurity.jpg',
                'training_objective' => 'Understand and mitigate digital threats.',
                'session_type' => 'Live',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'trainer_id' => 3,
                'training_type' => 'recorded',

                'training_title' => 'Creative Writing for Beginners',
                'training_sub_title' => 'Unlock Your Imagination',
                'training_descriptions' => 'Learn plot building, character development, and storytelling.',
                'training_category' => 'Soft Skills',
                'training_price' => 250.00,
                'thumbnail_file_path' => 'uploads/thumbnails/creative-writing.jpg',
                'thumbnail_file_name' => 'creative-writing.jpg',
                'training_objective' => 'Build your first short story with confidence.',
                'session_type' => 'Pre-recorded',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'trainer_id' => 4,
                'training_type' => 'Offline',
                'training_title' => 'Agile & Scrum Certification',
                'training_sub_title' => 'Become a Certified Scrum Master',
                'training_descriptions' => 'Deep dive into agile methodology and scrum ceremonies.',
                'training_category' => 'Technical',
                'training_price' => 899.99,
                'thumbnail_file_path' => 'uploads/thumbnails/agile.jpg',
                'thumbnail_file_name' => 'agile.jpg',
                'training_objective' => 'Run effective sprints and deliver value.',
                'session_type' => 'Live',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'trainer_id' => 5,

                'training_type' => 'online',

                'training_title' => 'Digital Marketing Strategy',
                'training_sub_title' => 'SEO, SEM, Social Media',
                'training_descriptions' => 'Grow business visibility using digital marketing tools.',
                'training_category' => 'Marketing',
                'training_price' => 499.00,
                'thumbnail_file_path' => 'uploads/thumbnails/digital-marketing.jpg',
                'thumbnail_file_name' => 'digital-marketing.jpg',
                'training_objective' => 'Create and manage online campaigns effectively.',
                'session_type' => 'Pre-recorded',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'trainer_id' => 2,

                'training_type' => 'recorded',

                'training_title' => 'Machine Learning Crash Course',
                'training_sub_title' => 'ML Concepts & Python Code',
                'training_descriptions' => 'From linear regression to model deployment with real datasets.',
                'training_category' => 'Technical',
                'training_price' => 999.00,
                'thumbnail_file_path' => 'uploads/thumbnails/machine-learning.jpg',
                'thumbnail_file_name' => 'machine-learning.jpg',
                'training_objective' => 'Build and deploy ML models using Python.',
                'session_type' => 'Live',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
