<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobseekerCartItem;
class JobseekerCartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run(): void
    {
        // Example static data; adjust as needed
        JobseekerCartItem::insert([
            [
                'jobseeker_id' => 1,
                'trainer_id' => 1,
                'material_id' => 1,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jobseeker_id' => 2,
                'trainer_id' => 1,
                'material_id' => 2,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jobseeker_id' => 3,
                'trainer_id' => 2,
                'material_id' => 3,
                'status' => 'purchased',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
