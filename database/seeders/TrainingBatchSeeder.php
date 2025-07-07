<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TrainingBatchSeeder extends Seeder
{
    public function run(): void
    {
        // Example values (ensure these exist in your DB or adjust accordingly)
        $trainerId = 1;
        $trainingMaterialId = 1;

        DB::table('training_batches')->insert([
            [
                'trainer_id' => $trainerId,
                'training_material_id' => $trainingMaterialId,
                'batch_no' => 'BATCH-001',
                'start_date' => '2025-07-10',
                'start_timing' => '10:00:00',
                'end_timing' => '12:00:00',
                'duration' => '2 hours',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => $trainerId,
                'training_material_id' => $trainingMaterialId,
                'batch_no' => 'BATCH-002',
                'start_date' => '2025-07-15',
                'start_timing' => '14:00:00',
                'end_timing' => '16:00:00',
                'duration' => '2 hours',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => $trainerId,
                'training_material_id' => $trainingMaterialId,
                'batch_no' => 'BATCH-003',
                'start_date' => '2025-07-20',
                'start_timing' => '09:00:00',
                'end_timing' => '11:00:00',
                'duration' => '2 hours',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
