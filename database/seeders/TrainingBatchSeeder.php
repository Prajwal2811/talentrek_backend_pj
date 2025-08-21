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
        $trainerId = 1;
        $trainingMaterialId = 1;

        DB::table('training_batches')->insert([
            [
                'trainer_id' => $trainerId,
                'training_material_id' => $trainingMaterialId,
                'batch_no' => 'BATCH-001',
                'start_date' => Carbon::now()->addDays(5)->toDateString(),
                'end_date' => Carbon::now()->addDays(5)->toDateString(),
                'start_timing' => '10:00:00',
                'end_timing' => '12:00:00',
                'duration' => '2 hours',
                'strength' => 20,
                'days' => json_encode(['Monday', 'Wednesday', 'Friday']),
                'location' => 'Online',
                'course_url' => 'https://example.com/course/1',
                'status' => 'Active',
                'zoom_start_url' => 'https://zoom.us/start/abc123',
                'zoom_join_url' => 'https://zoom.us/join/abc123',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => $trainerId,
                'training_material_id' => $trainingMaterialId,
                'batch_no' => 'BATCH-002',
                'start_date' => Carbon::now()->addDays(10)->toDateString(),
                'end_date' => Carbon::now()->addDays(10)->toDateString(),
                'start_timing' => '14:00:00',
                'end_timing' => '16:00:00',
                'duration' => '2 hours',
                'strength' => 25,
                'days' => json_encode(['Tuesday', 'Thursday']),
                'location' => 'Offline',
                'course_url' => null,
                'status' => 'Scheduled',
                'zoom_start_url' => null,
                'zoom_join_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trainer_id' => $trainerId,
                'training_material_id' => $trainingMaterialId,
                'batch_no' => 'BATCH-003',
                'start_date' => Carbon::now()->addDays(15)->toDateString(),
                'end_date' => Carbon::now()->addDays(15)->toDateString(),
                'start_timing' => '09:00:00',
                'end_timing' => '11:00:00',
                'duration' => '2 hours',
                'strength' => 30,
                'days' => json_encode(['Saturday']),
                'location' => 'Online',
                'course_url' => 'https://example.com/course/3',
                'status' => 'Completed',
                'zoom_start_url' => 'https://zoom.us/start/def456',
                'zoom_join_url' => 'https://zoom.us/join/def456',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
