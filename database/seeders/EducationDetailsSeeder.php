<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EducationDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $userTypes = [
            'jobseeker', 'recruiter', 'mentor',
            'coach', 'assessor', 'expat', 'trainer'
        ];

        $educationLevels = ["Bachelor's", "Master's", "Ph.D.", "Diploma", "High School"];
        $fieldsOfStudy = ['Computer Science', 'Mechanical Engineering', 'Psychology', 'Business Administration', 'Civil Engineering'];
        $institutions = ['IIT Bombay', 'Delhi University', 'TISS Mumbai', 'Anna University', 'BITS Pilani', 'JNU Delhi'];

        $data = [];

        $userIdCounter = 1;

        foreach ($userTypes as $userType) {
            for ($i = 0; $i < 5; $i++) {
                $data[] = [
                    'user_id'        => $userIdCounter++,
                    'user_type'      => $userType,
                    'high_education' => $educationLevels[array_rand($educationLevels)],
                    'field_of_study' => $fieldsOfStudy[array_rand($fieldsOfStudy)],
                    'institution'    => $institutions[array_rand($institutions)],
                    'graduate_year'  => rand(2010, 2023),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
        }

        DB::table('education_details')->insert($data);
    }
}
