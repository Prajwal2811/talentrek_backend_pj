<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('coupons')->insert([
            [
                'code'           => 'WELCOME10',
                'discount_type'  => 'percentage',
                'discount_value' => 10.00,
                // 'valid_from'     => now(),
                // 'valid_to'       => now()->addMonths(1),
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'FLAT200',
                'discount_type'  => 'fixed',
                'discount_value' => 200.00,
                // 'valid_from'     => now(),
                // 'valid_to'       => now()->addMonths(2),
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'SUMMER25',
                'discount_type'  => 'percentage',
                'discount_value' => 25.00,
                // 'valid_from'     => now(),
                // 'valid_to'       => now()->addMonths(3),
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
