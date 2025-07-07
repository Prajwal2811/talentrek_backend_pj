<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Prajwal Ingole',
            'email' => 'prajwal@talentrek.com',
            'phone' => '9975239057',
            'password' => bcrypt('prajwal@talentrek'),
            'pass' => 'prajwal@talentrek',
            'role' => 'superadmin',
            'status' => 'active',
        ]);

        Admin::create([
            'name' => 'Sumesh Chaure',
            'email' => 'sumesh@talentrek.com',
            'phone' => '9975239057',
            'password' => bcrypt('sumesh@talentrek'),
            'pass' => 'sumesh@talentrek',
            'role' => 'superadmin',
            'status' => 'active',
        ]);

        Admin::create([
            'name' => 'Hemchandra',
            'email' => 'hemchandra@talentrek.com',
            'phone' => '9975239063',
            'password' => bcrypt('hemchandra@talentrek'),
            'pass' => 'hemchandra@talentrek',
            'role' => 'admin',
            'status' => 'active',
        ]);

        Admin::create([
            'name' => 'Nimish Gupta',
            'email' => 'nimish@talentrek.com',
            'phone' => '9975239064',
            'password' => bcrypt('nimish@talentrek'),
            'pass' => 'nimish@talentrek',
            'role' => 'admin',
            'status' => 'active',
        ]);
    }
}
