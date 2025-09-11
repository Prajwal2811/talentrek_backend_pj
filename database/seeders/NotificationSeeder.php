<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notifications')->insert([
            [
                'sender_id' => 1,
                'sender_type' => 'jobseeker',
                'receiver_id' => 1,
                'user_type' => 'admin',
                'message' => 'New job application received.',
                'type' => 1, // Text
                'is_read' => 0,
                'is_read_admin' => 0,
                'is_read_users' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sender_id' => 2,
                'sender_type' => 'trainer',
                'receiver_id' => 1,
                'user_type' => 'admin',
                'message' => 'Trainer uploaded new course material.',
                'type' => 2, // File
                'is_read' => 0,
                'is_read_admin' => 0,
                'is_read_users' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sender_id' => 3,
                'sender_type' => 'mentor',
                'receiver_id' => 4,
                'user_type' => 'jobseeker',
                'message' => 'Mentor has accepted your session request.',
                'type' => 1,
                'is_read' => 1,
                'is_read_admin' => null,
                'is_read_users' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
