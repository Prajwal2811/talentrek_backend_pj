<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jobseekers;
use App\Models\EducationDetails;
use App\Models\WorkExperience;
use App\Models\Skills;
use App\Models\AdditionalInfo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class JobseekerInformationSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['John Doe', 'Priya Sharma', 'Arjun Mehta', 'Sneha Rao', 'Ravi Kumar'];
        $emails = ['john@gmail.com', 'priya@gmail.com', 'arjun@gmail.com', 'sneha@gmail.com', 'ravi@gmail.com'];
        $phones = ['9876543210', '9876543211', '9876543212', '9876543213', '9876543214'];
        $cities = ['Mumbai', 'Delhi', 'Bangalore', 'Hyderabad', 'Chennai'];
        $genders = ['Male', 'Female', 'Male', 'Female', 'Male'];

        foreach (range(0, 4) as $i) {
            $password = 'Password@' . ($i + 1);

            // Create jobseeker
            $jobseeker = Jobseekers::create([
                'name' => $names[$i],
                'email' => $emails[$i],
                'phone_number' => $phones[$i],
                'phone_code' => '+91',
                'gender' => $genders[$i],
                'date_of_birth' => now()->subYears(25 + $i)->format('Y-m-d'),
                'city' => $cities[$i],
                'state' => 'State ' . $i,
                'address' => 'Address for ' . $names[$i],
                'pin_code' => '4000' . $i,
                'country' => 'India',
                'password' => Hash::make($password),
                'pass' => $password,
                'avatar' => 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg',
                'google_id' => null,
                'otp' => rand(100000, 999999),
                'status' => 'active',
                'admin_status' => 'superadmin_approved',
                'shortlist' => null,
                'admin_recruiter_status' => null,
                'is_registered' => '1',
                'isSubscribtionBuy' => 'yes',
                'zoom_access_token' => null,
                'zoom_refresh_token' => null,
                'zoom_token_expires_at' => null,
            ]);

            // Add education
            foreach (range(1, 2) as $j) {
                EducationDetails::create([
                    'user_id' => $jobseeker->id,
                    'user_type' => 'jobseeker',
                    'high_education' => $j === 1 ? 'Bachelor of Tech' : 'Master of Tech',
                    'field_of_study' => 'Engineering',
                    'institution' => 'Institution ' . $j,
                    'graduate_year' => 2015 + $j + $i,
                ]);
            }

            // Add work experience
            foreach (range(1, 2) as $j) {
                WorkExperience::create([
                    'user_id' => $jobseeker->id,
                    'user_type' => 'jobseeker',
                    'job_role' => $j === 1 ? 'Developer' : 'Senior Developer',
                    'organization' => 'Company ' . $j,
                    'starts_from' => now()->subYears(5 - $j)->format('Y-m-d'),
                    'end_to' => now()->subYears(3 - $j)->format('Y-m-d'),
                ]);
            }

            // Add skills
            Skills::create([
                'jobseeker_id' => $jobseeker->id,
                'skills' => 'Laravel, React, SQL',
                'interest' => 'Web Development, AI',
                'job_category' => 'Software Engineering',
                'website_link' => 'https://jobseeker' . ($i + 1) . '.dev',
                'portfolio_link' => 'https://portfolio.jobseeker' . ($i + 1) . '.dev',
            ]);

            // Add resume
            AdditionalInfo::create([
                'user_id' => $jobseeker->id,
                'user_type' => 'jobseeker',
                'doc_type' => 'resume',
                'document_name' => 'resume_' . $jobseeker->name . '.pdf',
                'document_path' => 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg',
            ]);

            // Add profile picture
            AdditionalInfo::create([
                'user_id' => $jobseeker->id,
                'user_type' => 'jobseeker',
                'doc_type' => 'profile_picture',
                'document_name' => 'profile_' . $jobseeker->name . '.png',
                'document_path' => 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg',
            ]);
        }
    }
}
