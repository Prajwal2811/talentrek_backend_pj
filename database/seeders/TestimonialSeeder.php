<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    public function run()
    {
        DB::table('testimonials')->insert([
            [
                'name' => 'Amit Sharma',
                'designation' => 'Software Engineer',
                'message' => 'This platform has significantly helped me grow my career. Highly recommended!',
                'file_name' => 'amit.jpg',
                'file_path' => 'uploads/testimonials/amit.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samantha Lee',
                'designation' => 'Product Manager',
                'message' => 'Amazing experience! The tools and mentorship were top-notch.',
                'file_name' => 'samantha.jpg',
                'file_path' => 'uploads/testimonials/samantha.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ravi Kumar',
                'designation' => 'UX Designer',
                'message' => 'Thanks to this team, I landed my dream job!',
                'file_name' => 'ravi.jpg',
                'file_path' => 'uploads/testimonials/ravi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emily Chen',
                'designation' => 'HR Specialist',
                'message' => 'The support and resources here are invaluable.',
                'file_name' => 'emily.jpg',
                'file_path' => 'uploads/testimonials/emily.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Arjun Mehta',
                'designation' => null,
                'message' => 'Great platform for freshers and experienced professionals alike.',
                'file_name' => null,
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
