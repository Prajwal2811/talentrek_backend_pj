<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecruitersSeeder extends Seeder
{
    public function run()
    {
        DB::table('recruiters')->insert([
            [
                'name' => 'Rahul Mehta',
                'email' => 'rahul@techsolutions.com',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sneha Patil',
                'email' => 'sneha@fincorp.com',
                'status' => 'inactive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Ankit Sharma',
                'email' => 'ankit@edunation.com',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Priya Verma',
                'email' => 'priya@healthbridge.com',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Karan Kapoor',
                'email' => 'karan@greentech.com',
                'status' => 'inactive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
