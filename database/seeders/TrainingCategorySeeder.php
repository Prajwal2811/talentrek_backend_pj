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
            'Health & Safety'
        ];

        foreach ($categories as $category) {
            TrainingCategory::create([
                'category' => $category
            ]);
        }
    }
}
