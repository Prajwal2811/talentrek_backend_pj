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
                    'training_descriptions' => "- Learn HTML, CSS, JavaScript, PHP, and Laravel\n- Build full-stack responsive web applications\n- Master both frontend and backend development\n- Work on real-world projects for hands-on experience",
                    'training_category' => 'Technical',
                    'training_price' => 799.00,
                    'training_offer_price' => 500.00,
                    'thumbnail_file_path' => 'uploads/thumbnails/fullstack.jpg',
                    'thumbnail_file_name' => 'fullstack.jpg',
                    'training_objective' => 'Build responsive full-stack apps with backend logic.',
                    'session_type' => 'Live',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'trainer_id' => 2,
                    'training_type' => 'classroom',
                    'training_title' => 'Time Management Mastery',
                    'training_sub_title' => 'Boost Productivity and Efficiency',
                    'training_descriptions' => "- Identify personal and professional time wasters\n- Learn time-blocking and goal setting techniques\n- Improve productivity with tested frameworks\n- Participate in group activities and planning tools",
                    'training_category' => 'Soft Skills',
                    'training_price' => 299.00,
                    'training_offer_price' => 500.00,
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
                    'training_descriptions' => "- Master Excel formulas and data cleaning\n- Create pivot tables and interactive dashboards\n- Use advanced functions like VLOOKUP and IF statements\n- Build financial and business reports efficiently",
                    'training_category' => 'Technical',
                    'training_price' => 399.50,
                    'training_offer_price' => 500.00,
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
                    'training_descriptions' => "- Learn the 5 phases: Empathize, Define, Ideate, Prototype, Test\n- Use real-world case studies to apply concepts\n- Improve creative problem-solving skills\n- Foster innovative thinking through design challenges",
                    'training_category' => 'Leadership',
                    'training_price' => 599.00,
                    'training_offer_price' => 500.00,
                    'thumbnail_file_path' => 'uploads/thumbnails/design-thinking.jpg',
                    'thumbnail_file_name' => 'design-thinking.jpg',
                    'training_objective' => 'Apply human-centered design to real-world issues.',
                    'session_type' => 'Pre-recorded',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'trainer_id' => 1,
                    'training_type' => 'classroom',
                    'training_title' => 'Communication for Managers',
                    'training_sub_title' => 'Lead with Clarity and Confidence',
                    'training_descriptions' => "- Improve verbal and non-verbal communication skills\n- Practice persuasive speaking and active listening\n- Learn strategies for team and stakeholder communication\n- Handle tough conversations with confidence",
                    'training_category' => 'Soft Skills',
                    'training_price' => 449.00,
                    'training_offer_price' => 500.00,
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
                    'training_descriptions' => "- Understand common cyber threats and attack types\n- Learn data protection and encryption basics\n- Explore firewalls, VPNs, and antivirus solutions\n- Build personal and organizational cybersecurity awareness",
                    'training_category' => 'Technical',
                    'training_price' => 699.00,
                    'training_offer_price' => 500.00,
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
                    'training_descriptions' => "- Discover storytelling fundamentals and structure\n- Develop characters, dialogue, and plotlines\n- Practice writing short stories with guided prompts\n- Edit and refine your first creative writing piece",
                    'training_category' => 'Soft Skills',
                    'training_price' => 250.00,
                    'training_offer_price' => 500.00,
                    'thumbnail_file_path' => 'uploads/thumbnails/creative-writing.jpg',
                    'thumbnail_file_name' => 'creative-writing.jpg',
                    'training_objective' => 'Build your first short story with confidence.',
                    'session_type' => 'Pre-recorded',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'trainer_id' => 4,
                    'training_type' => 'classroom',
                    'training_title' => 'Agile & Scrum Certification',
                    'training_sub_title' => 'Become a Certified Scrum Master',
                    'training_descriptions' => "- Learn Agile principles and Scrum methodology\n- Understand sprint planning, reviews, and retrospectives\n- Prepare for Scrum Master certification\n- Use tools like Jira to manage Agile projects",
                    'training_category' => 'Technical',
                    'training_price' => 899.99,
                    'training_offer_price' => 500.00,
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
                    'training_descriptions' => "- Master SEO, SEM, and social media marketing\n- Build and manage effective online ad campaigns\n- Use analytics tools to track performance\n- Create digital strategies that grow business visibility",
                    'training_category' => 'Marketing',
                    'training_price' => 499.00,
                    'training_offer_price' => 500.00,
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
                    'training_descriptions' => "- Understand supervised and unsupervised learning\n- Implement ML algorithms in Python using scikit-learn\n- Evaluate model accuracy and performance\n- Build and deploy models on real-world datasets",
                    'training_category' => 'Technical',
                    'training_price' => 999.00,
                    'training_offer_price' => 500.00,
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
