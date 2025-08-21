<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CoachSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('coaches')->insert([
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'national_id' => 'A123456789',
                'phone_code' => '+1',
                'phone_number' => '9876543210',
                'date_of_birth' => '1985-12-20',
                'city' => 'New York',
                'password' => Hash::make('password123'),
                'pass' => 'password123',
                'otp' => '123456',
                'status' => 'active',
                'admin_status' => 'approved',
                'inactive_reason' => null,
                'rejection_reason' => null,
                'shortlist' => 'yes',
                'admin_recruiter_status' => 'verified',
                'google_id' => 'google_1234567890',
                'avatar' => 'default.png',
                'about_coach' => 'Certified life coach with 10+ years of experience.',
                'isSubscribtionBuy' => 'yes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'national_id' => 'B987654321',
                'phone_code' => '+91',
                'phone_number' => '9999999999',
                'date_of_birth' => '1990-05-10',
                'city' => 'Mumbai',
                'password' => Hash::make('secret456'),
                'pass' => 'secret456',
                'otp' => '654321',
                'status' => 'inactive',
                'admin_status' => 'pending',
                'inactive_reason' => 'Incomplete documents',
                'rejection_reason' => null,
                'shortlist' => 'no',
                'admin_recruiter_status' => 'pending',
                'google_id' => null,
                'avatar' => null,
                'about_coach' => 'Specialist in personal development and career coaching.',
                'isSubscribtionBuy' => 'no',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
