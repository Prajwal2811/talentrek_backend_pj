<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingCategory;

class TrainingCategorySeeder extends Seeder
{
     public function run(): void
    {
        $categories = [
            'Technical Skills',
            'Soft Skills',
            'Leadership',
            'Communication',
            'Time Management',
            'Project Management',
            'Customer Service',
            'Sales Training',
            'Compliance Training',
            'Health & Safety',
            'Other',
        ];

        foreach ($categories as $category) {
            TrainingCategory::create([
                'category' => $category,
                'image_path' => 'uploads/training_categories/default.png', // Default placeholder
                'image_name' => 'default.png'
            ]);
        }
    }
}
