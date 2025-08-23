<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecruitersSeeder extends Seeder
{
    public function run()
    {
        DB::table('recruiters')->insert([
            [
                'name' => 'Rahul Mehta',
                'email' => 'rahul@techsolutions.com',
                'national_id' => 'IND123456',
                'status' => 'active',
                'password' => bcrypt('password123'),
                'pass' => 'password123',
                'otp' => 1234,
                'role' => 'main',
                'recruiter_of' => null, // main recruiter, no parent
                'company_id' => '1',
                'inactive_reason' => null,
                'admin_status' => 'approved',
                'rejection_reason' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sneha Patil',
                'email' => 'sneha@fincorp.com',
                'national_id' => 'IND123457',
                'status' => 'inactive',
                'password' => bcrypt('password123'),
                'pass' => 'password123',
                'otp' => 5678,
                'role' => 'sub_recruiter',
                'recruiter_of' => 1, // belongs to Rahul
                'company_id' => '2',
                'inactive_reason' => 'Company policy change',
                'admin_status' => 'pending',
                'rejection_reason' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Ankit Sharma',
                'email' => 'ankit@edunation.com',
                'national_id' => 'IND123458',
                'status' => 'active',
                'password' => bcrypt('password123'),
                'pass' => 'password123',
                'otp' => 4321,
                'role' => 'main',
                'recruiter_of' => null, // main recruiter
                'company_id' => '2',
                'inactive_reason' => null,
                'admin_status' => 'approved',
                'rejection_reason' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Priya Verma',
                'email' => 'priya@healthbridge.com',
                'national_id' => 'IND123459',
                'status' => 'active',
                'password' => bcrypt('password123'),
                'pass' => 'password123',
                'otp' => 8765,
                'role' => 'main',
                'recruiter_of' => null, // main recruiter
                'company_id' => '2',
                'inactive_reason' => null,
                'admin_status' => 'approved',
                'rejection_reason' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Karan Kapoor',
                'email' => 'karan@greentech.com',
                'national_id' => 'IND123460',
                'status' => 'inactive',
                'password' => bcrypt('password123'),
                'pass' => 'password123',
                'otp' => 9999,
                'role' => 'sub_recruiter',
                'recruiter_of' => 3, // belongs to Ankit
                'company_id' => '2',
                'inactive_reason' => 'Subscription expired',
                'admin_status' => 'rejected',
                'rejection_reason' => 'Incomplete documents',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
