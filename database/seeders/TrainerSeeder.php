<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainerSeeder extends Seeder
{
    public function run()
    {
        DB::table('trainers')->insert([
            [
                'name' => 'Amit Sharma',
                'email' => 'amit.sharma@example.com',
                'phone_code' => '+91',
                'phone_number' => '9876543210',
                'date_of_birth' => '1990-03-15',
                'city' => 'Mumbai',
                'otp' => '123456',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samantha Lee',
                'email' => 'samantha.lee@example.com',
                'phone_code' => '+1',
                'phone_number' => '4085557890',
                'date_of_birth' => '1985-07-24',
                'city' => 'San Francisco',
                'otp' => '654321',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ravi Kumar',
                'email' => 'ravi.kumar@example.com',
                'phone_code' => '+91',
                'phone_number' => '9998887776',
                'date_of_birth' => '1988-12-10',
                'city' => 'Delhi',
                'otp' => '789456',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emily Chen',
                'email' => 'emily.chen@example.com',
                'phone_code' => '+44',
                'phone_number' => '7512345678',
                'date_of_birth' => '1992-05-20',
                'city' => 'London',
                'otp' => '321654',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Arjun Mehta',
                'email' => 'arjun.mehta@example.com',
                'phone_code' => '+91',
                'phone_number' => '9123456789',
                'date_of_birth' => '1995-11-02',
                'city' => 'Bangalore',
                'otp' => '147258',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
