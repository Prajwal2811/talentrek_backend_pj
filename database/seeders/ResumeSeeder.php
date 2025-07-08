<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('resumes_format')->insert([
            'resume' => 'uploads/sample_resume.docx',  // Ensure this file exists or update the path
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
