<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SocialMediaSeeder extends Seeder
{
    public function run()
    {
        DB::table('social_media')->insert([
            [
                'media_name'  => 'Facebook',
                'icon_class'  => 'fab fa-facebook-f',
                'media_link'  => 'https://facebook.com',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'media_name'  => 'Twitter',
                'icon_class'  => 'fa-brands fa-twitter',
                'media_link'  => 'https://twitter.com',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'media_name'  => 'Instagram',
                'icon_class'  => 'fa-brands fa-instagram',
                'media_link'  => 'https://instagram.com',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'media_name'  => 'Ticktoc',
                'icon_class'  => 'fa-brands fa-tiktok',
                'media_link'  => 'https://ticktoc.com',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ]);
    }
}
