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
        DB::table('team_course_members')->insert([
            [
                'purchase_id' => 1,
                'email' => 'member1@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'purchase_id' => 1,
                'email' => 'member2@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'purchase_id' => 2,
                'email' => 'member3@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

}
