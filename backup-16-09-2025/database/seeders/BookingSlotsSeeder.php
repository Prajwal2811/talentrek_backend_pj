<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSlotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userTypes = ['mentor', 'coach', 'assessor'];
        $slotModes = ['online', 'offline'];

        foreach (range(1, 20) as $i) {
            $userType = $userTypes[array_rand($userTypes)];

            // Dummy IDs assuming you already have mentors/coaches/assessors with IDs 1 to 10
            $userId = rand(1, 10);

            DB::table('booking_slots')->insert([
                'user_type' => $userType,
                'user_id' => $userId,
                'slot_mode' => $slotModes[array_rand($slotModes)],
                'start_time' => now()->setTime(rand(8, 12), 0),
                'end_time' => now()->setTime(rand(13, 18), 0),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
