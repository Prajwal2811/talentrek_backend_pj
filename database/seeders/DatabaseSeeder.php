<?php

namespace Database\Seeders;

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

          $this->call([
            JobseekerInformationSeeder::class,
            SocialMediaSeeder::class,
            SiteSettingsSeeder::class,
            SectionContentSeeder::class,
            RecruitersSeeder::class,
            RecruitersCompanySeeder::class,
        ]);
        
        

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

        \App\Models\Admin::factory()->create([
            'name' => 'Hemchandra',
            'email' => 'hemchandra@talentrek.com',
            'phone' => '9975239063',
            'password' => bcrypt('hemchandra@talentrek'),
            'pass' => 'hemchandra@talentrek',
            'role' => 'admin',
            'status' => 'active',
        ]);

        \App\Models\Admin::factory()->create([
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
