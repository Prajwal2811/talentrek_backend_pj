<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('site_settings')->insert([
            'header_logo' => 'https://talentrek.reviewdevelopment.net/asset/images/logo.png',
            'footer_logo' => 'https://talentrek.reviewdevelopment.net/asset/images/logo.png',
            'favicon'     => 'https://talentrek.reviewdevelopment.net/asset/images/logo.png',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
