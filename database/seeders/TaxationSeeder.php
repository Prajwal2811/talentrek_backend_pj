<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaxationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('taxations')->insert([
            [
                'name'       => 'GST',
                'type'       => 'percentage',
                'rate'       => 18.00,
                'user_type'  => 'mentor',
                'is_active'  => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'VAT',
                'type'       => 'percentage',
                'rate'       => 12.50,
                'user_type'  => 'trainer',
                'is_active'  => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Service Tax',
                'type'       => 'percentage',
                'rate'       => 15.00,
                'user_type'  => 'assessor',
                'is_active'  => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Fixed Platform Fee',
                'type'       => 'fixed',
                'rate'       => 50.00,
                'user_type'  => 'coach',
                'is_active'  => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
