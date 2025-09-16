<?php

namespace Database\Seeders;

use App\Models\PaymentHistory;
use App\Models\Jobseekers;
use App\Models\TrainingMaterial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $jobseekers = Jobseekers::all();
        $courses = TrainingMaterial::all();

        if ($jobseekers->isEmpty() || $courses->isEmpty()) {
            $this->command->warn("Please seed jobseekers and training_materials tables first.");
            return;
        }

        $statuses   = ['pending', 'completed', 'failed', 'refunded'];
        $methods    = ['UPI', 'Card'];
        $currencies = ['INR', 'USD', 'EUR'];
        $coupons    = [null, 'WELCOME10', 'DISCOUNT20', 'FREESHIP']; // some demo coupons

        $receiverTypes = ['trainer', 'mentor', 'coach', 'assessor', 'talentrek'];

        foreach (range(1, 10) as $i) {
            $jobseeker = $jobseekers->random();
            $course    = $courses->random();

            foreach ($receiverTypes as $receiver) {
                // base price
                $basePrice = $course->training_price;

                // random tax (5%–18%)
                $tax = round($basePrice * (rand(5, 18) / 100), 2);

                // random coupon
                $appliedCoupon = $coupons[array_rand($coupons)];

                PaymentHistory::create([
                    'user_type'      => 'jobseeker',
                    'user_id'        => $jobseeker->id,
                    'receiver_type'  => $receiver,
                    'receiver_id'    => $receiver === 'trainer' ? $course->trainer_id : null, 
                    'payment_for'    => $receiver === 'trainer' ? 'training' : 'subscription',
                    'amount_paid'    => $basePrice + $tax,
                    'tax'            => $tax,
                    'applied_coupon' => $appliedCoupon,
                    'payment_status' => $statuses[array_rand($statuses)],
                    'transaction_id' => strtoupper(Str::random(12)),
                    'track_id'       => strtoupper(Str::random(12)),
                    'order_id'       => 'ORD-' . $jobseeker->id . '-' . strtoupper(Str::random(8)),
                    'currency'       => $currencies[array_rand($currencies)],
                    'payment_method' => $methods[array_rand($methods)],
                    'paid_at'        => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }


}
