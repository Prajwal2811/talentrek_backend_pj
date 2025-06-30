<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Admin::factory()->create([
            'name' => 'Prajwal Ingole',
            'email' => 'prajwal@talentrek.com',
            'phone' => '9975239057',
            'password' => bcrypt('prajwal@talentrek'), // important to hash this
            'pass' => 'prajwal@talentrek', // important to hash this
            'role' => 'superadmin',
            'status' => 'active',
        ]);

        \App\Models\Admin::factory()->create([
            'name' => 'Sumesh Chaure',
            'email' => 'sumesh@talentrek.com',
            'phone' => '9975239057',
            'password' => bcrypt('sumesh@talentrek'), // important to hash this
            'pass' => 'sumesh@talentrek', // important to hash this
            'role' => 'superadmin',
            'status' => 'active',
        ]);
    }
}
