<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'user_type' => 'jobseeker',
                'title' => 'Standard',
                'price' => 150.00,
                'description' => 'Job Seeker standard plan billed annually.',
                'features' => ['Access to job listings', 'Apply to jobs', 'Email notifications'],
                'duration_days' => 365,
                'slug' => 'jobseeker_standard'
            ],
            [
                'user_type' => 'jobseeker',
                'title' => 'Job Seeker+ (First Year)',
                'price' => 600.00,
                'description' => 'Premium Job Seeker plan for the first year, then $150/year afterwards.',
                'features' => ['Everything in Standard', 'Priority listing', 'Direct recruiter messages'],
                'duration_days' => 365,
                'slug' => 'jobseeker_plus'

            ],
            [
                'user_type' => 'trainer',
                'title' => 'Trainer Plan',
                'price' => 150.00,
                'description' => 'Annual subscription for trainers.',
                'features' => ['Post training sessions', 'Manage students', 'Track progress'],
                'duration_days' => 365,
                'slug' => 'trainer_standard'
            ],
            [
                'user_type' => 'mentor',
                'title' => 'Mentor Plan',
                'price' => 150.00,
                'description' => 'Annual subscription for mentors.',
                'features' => ['Host mentorship sessions', 'Manage mentees', 'Session analytics'],
                'duration_days' => 365,
                 'slug' => 'mentor_standard'
            ],
            [
                'user_type' => 'assessor',
                'title' => 'Evaluator/Assessor Plan',
                'price' => 200.00,
                'description' => 'Annual subscription for evaluators/assessors.',
                'features' => ['Evaluate candidates', 'Report generation', 'Assessment tools'],
                'duration_days' => 365,
                 'slug' => 'assessor_standard'
            ],
            [
                'user_type' => 'coach',
                'title' => 'Coach Plan',
                'price' => 200.00,
                'description' => 'Annual subscription for coaches.',
                'features' => ['Manage coaching sessions', 'Client tracking', 'Goal setting tools'],
                'duration_days' => 365,
                 'slug' => 'coach_standard'
            ],
            [
                'user_type' => 'recruiter',
                'title' => 'Recruiter Plan',
                'price' => 5500.00,
                'description' => 'Annual subscription for recruiters.',
                'features' => ['Post unlimited jobs', 'Search candidates', 'Advanced filtering'],
                'duration_days' => 365,
                'slug' => 'recruiter_standard'
            ],
            [
                'user_type' => 'recruiter',
                'title' => 'Corporate (3 Recruiters)',
                'price' => 13500.00,
                'description' => 'Corporate annual plan for up to 3 recruiters.',
                'features' => ['Corporate dashboard', 'Team access for 3 recruiters', 'Advanced analytics'],
                'duration_days' => 365,
                'slug' => 'corporate_3_recruiters'
            ],
            [
                'user_type' => 'recruiter',
                'title' => 'Corporate (4-6 Recruiters)',
                'price' => 20000.00,
                'description' => 'Corporate annual plan for 4 to 6 recruiters.',
                'features' => ['Corporate dashboard', 'Team access for 4-6 recruiters', 'Advanced analytics'],
                'duration_days' => 365,
                'slug' => 'corporate_4_to_6_recruiters'
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('subscription_plans')->insert([
                'user_type' => $plan['user_type'],
                'year' => date('Y'),
                'title' => $plan['title'],
                'price' => $plan['price'],
                'description' => $plan['description'],
                'features' => json_encode($plan['features']),
                'duration_days' => $plan['duration_days'],
                'is_active' => true,
                'slug' => $plan['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
