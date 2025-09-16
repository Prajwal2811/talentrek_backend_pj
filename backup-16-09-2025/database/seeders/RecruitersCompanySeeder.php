<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RecruitersCompanySeeder extends Seeder
{
    public function run()
    {
        DB::table('recruiters_company')->insert([
            [
                'recruiter_id' => 1,
                'company_name' => 'TechSolutions Pvt Ltd',
                'company_website' => 'https://techsolutions.com',
                'company_city' => 'Bangalore',
                'company_address' => '123, Tech Park',
                'business_email' => 'contact@techsolutions.com',
                'phone_code' => '+91',
                'company_phone_number' => '9876543210',
                'password' => Hash::make('pass123'),
                'pass' => 'pass123',
                'otp' => 123456,
                'no_of_employee' => '51-200',
                'industry_type' => 'IT',
                'status' => 'inactive',
                'registration_number' => 'TS001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'recruiter_id' => 2,
                'company_name' => 'FinCorp India',
                'company_website' => 'https://fincorp.com',
                'company_city' => 'Mumbai',
                'company_address' => '45, Finance Street',
                'business_email' => 'support@fincorp.com',
                'phone_code' => '+91',
                'company_phone_number' => '9123456789',
                'password' => Hash::make('pass123'),
                'pass' => 'pass123',
                'otp' => 234567,
                'no_of_employee' => '201-500',
                'industry_type' => 'Finance',
                'status' => 'inactive',
                'registration_number' => 'FC002',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'recruiter_id' => 3,
                'company_name' => 'EduNation Ltd',
                'company_website' => 'https://edunation.com',
                'company_city' => 'Delhi',
                'company_address' => '78, Knowledge Park',
                'business_email' => 'info@edunation.com',
                'phone_code' => '+91',
                'company_phone_number' => '9988776655',
                'password' => Hash::make('pass123'),
                'pass' => 'pass123',
                'otp' => 345678,
                'no_of_employee' => '11-50',
                'industry_type' => 'Education',
                'status' => 'inactive',
                'registration_number' => 'EN003',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'recruiter_id' => 4,
                'company_name' => 'HealthBridge Pvt Ltd',
                'company_website' => 'https://healthbridge.com',
                'company_city' => 'Hyderabad',
                'company_address' => '67, Wellness Lane',
                'business_email' => 'care@healthbridge.com',
                'phone_code' => '+91',
                'company_phone_number' => '9090909090',
                'password' => Hash::make('pass123'),
                'pass' => 'pass123',
                'otp' => 456789,
                'no_of_employee' => '100+',
                'industry_type' => 'Healthcare',
                'status' => 'inactive',
                'registration_number' => 'HB004',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'recruiter_id' => 5,
                'company_name' => 'GreenTech Innovations',
                'company_website' => 'https://greentech.com',
                'company_city' => 'Pune',
                'company_address' => '22, Eco Street',
                'business_email' => 'hello@greentech.com',
                'phone_code' => '+91',
                'company_phone_number' => '8080808080',
                'password' => Hash::make('pass123'),
                'pass' => 'pass123',
                'otp' => 567890,
                'no_of_employee' => '1-10',
                'industry_type' => 'Environment',
                'registration_number' => 'GT005',
                'status' => 'inactive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
