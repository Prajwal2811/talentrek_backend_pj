<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AssessorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('assessors')->insert([
            [
                'name' => 'Ravi Kumar',
                'email' => 'ravi.assessor@example.com',
                'national_id' => 'IND-100001',
                'phone_code' => '+91',
                'phone_number' => '9876543210',
                'date_of_birth' => '1980-06-15',
                'city' => 'Delhi',
                'password' => Hash::make('raviPass123'),
                'pass' => 'raviPass123',
                'otp' => '789456',
                'status' => 'active',
                'admin_status' => 'approved',
                'inactive_reason' => null,
                'rejection_reason' => null,
                'shortlist' => 'yes',
                'admin_recruiter_status' => 'verified',
                'google_id' => 'google_assessor_001',
                'avatar' => 'avatar_ravi.png',
                'about_assessor' => 'Certified assessor with experience in skill evaluation for over 12 years.',
                'isSubscribtionBuy' => 'yes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fatima Noor',
                'email' => 'fatima.assessor@example.com',
                'national_id' => 'UAE-567890',
                'phone_code' => '+971',
                'phone_number' => '555123456',
                'date_of_birth' => '1992-03-22',
                'city' => 'Dubai',
                'password' => Hash::make('fatimaPass456'),
                'pass' => 'fatimaPass456',
                'otp' => '321789',
                'status' => 'inactive',
                'admin_status' => 'pending',
                'inactive_reason' => 'Verification documents pending',
                'rejection_reason' => null,
                'shortlist' => 'no',
                'admin_recruiter_status' => 'pending',
                'google_id' => null,
                'avatar' => null,
                'about_assessor' => 'Focused on assessment of technical and soft skills in the corporate sector.',
                'isSubscribtionBuy' => 'no',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
