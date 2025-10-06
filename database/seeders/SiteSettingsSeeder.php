<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('site_settings')->insert([
            'header_logo'              => 'https://talentrek.reviewdevelopment.net/asset/images/logo.png',
            'footer_logo'              => 'https://talentrek.reviewdevelopment.net/asset/images/logo.png',
            'favicon'                  => 'https://talentrek.reviewdevelopment.net/asset/images/logo.png',
            'trainingMaterialTax'      => 12.50,
            'trainingMaterialBatchTax' => 10.00,
            'coachTax'                 => 8.00,
            'mentorTax'                => 7.50,
            'assessorTax'              => 9.00,
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);
    }

}
