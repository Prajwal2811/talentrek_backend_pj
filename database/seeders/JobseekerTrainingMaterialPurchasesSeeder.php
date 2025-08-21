<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobseekerTrainingMaterialPurchasesSeeder extends Seeder
{
    public function run()
    {
        DB::table('jobseeker_training_material_purchases')->insert([
            [
                'jobseeker_id'   => 1,
                'trainer_id'     => 1,
                'material_id'    => 1,
                'training_type'  => 'online',
                'session_type'   => 'online',
                'batch_id'       => 1,
                'purchase_for'   => 'individual',
                'payment_id'     => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'jobseeker_id'   => 2,
                'trainer_id'     => 2,
                'material_id'    => 2,
                'training_type'  => 'recorded',
                'session_type'   => null,
                'batch_id'       => null,
                'purchase_for'   => 'team',
                'payment_id'     => 2,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
