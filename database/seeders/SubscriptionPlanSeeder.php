<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $userTypes = ['jobseeker', 'recruiter', 'mentor', 'coach', 'assessor', 'expat', 'trainer'];

        $plans = [
            [
                'title' => 'Silver',
                'price' => 49.00,
                'description' => 'Basic access with limited features.',
                'features' => ['Access to dashboard', 'Email support', 'Limited visibility'],
                'duration_days' => 30
            ],
            [
                'title' => 'Gold',
                'price' => 99.00,
                'description' => 'Advanced features and better visibility.',
                'features' => ['Everything in Silver', 'Priority support', 'Advanced analytics'],
                'duration_days' => 90
            ],
            [
                'title' => 'Platinum',
                'price' => 199.00,
                'description' => 'All features with maximum reach.',
                'features' => ['Everything in Gold', 'Dedicated manager', 'Top listing priority'],
                'duration_days' => 180
            ],
        ];

        foreach ($userTypes as $userType) {
            foreach ($plans as $plan) {
                DB::table('subscription_plans')->insert([
                    'user_type' => $userType,
                    'year' => date('Y'),
                    'title' => $plan['title'],
                    'price' => $plan['price'],
                    'description' => $plan['description'],
                    'features' => json_encode($plan['features']),
                    'duration_days' => $plan['duration_days'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
