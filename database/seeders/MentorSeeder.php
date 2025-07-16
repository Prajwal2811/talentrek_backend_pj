<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
class MentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mentors')->insert([
            [
                'name' => 'Prajwal Ingole',
                'email' => 'prajwal@example.com',
                'national_id' => 'ABC123456',
                'phone_code' => '+91',
                'phone_number' => '9876543210',
                'date_of_birth' => '1990-05-15',
                'city' => 'Mumbai',
                'password' => Hash::make('password123'),
                'pass' => 'password123',
                'status' => 'active',
                'otp' => '123456',
                'admin_status' => 'approved',
                'inactive_reason' => null,
                'rejection_reason' => null,
                'shortlist' => 'yes',
                'admin_recruiter_status' => null,
                'google_id' => Str::uuid(),
                'avatar' => 'avatar1.png',
                'isSubscribtionBuy' => 'yes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anjali Verma',
                'email' => 'anjali@example.com',
                'national_id' => 'XYZ987654',
                'phone_code' => '+1',
                'phone_number' => '5551234567',
                'date_of_birth' => '1988-11-23',
                'city' => 'New York',
                'password' => Hash::make('securepass'),
                'pass' => 'securepass',
                'status' => 'active',
                'otp' => '654321',
                'admin_status' => null,
                'inactive_reason' => 'Profile incomplete',
                'rejection_reason' => null,
                'shortlist' => null,
                'admin_recruiter_status' => null,
                'google_id' => Str::uuid(),
                'avatar' => 'avatar2.png',
                'isSubscribtionBuy' => 'no',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
