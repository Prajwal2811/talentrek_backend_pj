<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TeamCourseMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run(): void
    {
        // Fetch all valid purchase IDs from the purchases table
        $purchaseIds = DB::table('jobseeker_training_material_purchases')->pluck('id')->toArray();

        $members = [];

        for ($i = 1; $i <= 10; $i++) {
            $members[] = [
                'main_jobseeker_id' => $i, // make sure these jobseeker IDs exist
                'jobseeker_id' => rand(1, 20), // adjust according to trainer table
                'trainer_id' => rand(1, 3), // adjust according to trainer table
                'training_material_purchases_id' => $purchaseIds[array_rand($purchaseIds)],
                'material_id' => rand(1, 5),
                'training_type' => rand(0, 1) ? 'recorded' : 'online',
                'session_type' => rand(0, 1) ? 'online' : 'classroom',
                'batch_id' => rand(1, 3),
                'transaction_id' => 'TXN' . rand(100000, 999999),
                'payment_status' => ['pending', 'success', 'failed'][rand(0, 2)],
                'track_id' => 'TRK' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'email' => "member{$i}@example.com",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('team_course_members')->insert($members);

    }

}
