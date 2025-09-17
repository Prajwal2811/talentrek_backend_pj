<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingMaterialsDocument;

class TrainingMaterialsDocumentsSeeder extends Seeder
{
    public function run(): void
    {
        TrainingMaterialsDocument::create([
            'trainer_id' => 1,
            'training_material_id' => 1,
            'training_title' => 'UX Design Basics',
            'description' => 'Introductory video on UX principles and process.',
            'file_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'file_name' => 'ux-design-basics.mp4',
        ]);

        TrainingMaterialsDocument::create([
            'trainer_id' => 1,
            'training_material_id' => 1,
            'training_title' => 'Figma Complete Tutorial',
            'description' => 'Comprehensive tutorial covering key Figma features.',
            'file_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'file_name' => 'figma-tutorial.mp4',
        ]);

        TrainingMaterialsDocument::create([
            'trainer_id' => 2,
            'training_material_id' => 2,
            'training_title' => 'Advanced Prototyping',
            'description' => 'Learn advanced prototyping techniques with Figma.',
             'file_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'file_name' => 'advanced-prototyping.mp4',
        ]);

        TrainingMaterialsDocument::create([
            'trainer_id' => 2,
            'training_material_id' => 2,
            'training_title' => 'Design Systems Overview',
            'description' => 'An overview of how to build design systems effectively.',
            'file_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'file_name' => 'design-systems.mp4',
        ]);
    }
}
