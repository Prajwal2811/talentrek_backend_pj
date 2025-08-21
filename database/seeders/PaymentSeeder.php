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
        // Ensure you have jobseekers and courses already seeded
        $jobseekers = Jobseekers::all();
        $courses = TrainingMaterial::all();

        if ($jobseekers->isEmpty() || $courses->isEmpty()) {
            $this->command->warn("Please seed jobseekers and training_courses tables first.");
            return;
        }

        $statuses = ['pending', 'completed', 'failed', 'refunded'];
        $methods = ['UPI', 'Card'];

        foreach (range(1, 10) as $i) {
            $jobseeker = $jobseekers->random();
            $course = $courses->random();

            PaymentHistory::create([
                'jobseeker_id'      => $jobseeker->id,
                'material_id'         => $course->id,
                'transaction_id'    => strtoupper(Str::random(10)),
                'payment_reference' => strtoupper(Str::random(10)),
                'amount_paid'       => $course->training_price,
                'payment_status'    => $statuses[array_rand($statuses)],
                'payment_method'    => $methods[array_rand($methods)],
                'paid_at'           => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }
    }

}