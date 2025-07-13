<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class JobseekerSavedBookingSessionSeeder extends Seeder
{
    public function run()
    {
         DB::table('jobseeker_saved_booking_session')->insert([
            [
                'jobseeker_id'               => 1,
                'user_type'                  => 'mentor',
                'user_id'                    => 1,
                'booking_slot_id'            => 1,
                'slot_date'                  => Carbon::now()->addDays(2)->toDateString(),
                'slot_time'                  => '10:00:00 - 12:00:00',
                'status'                     => 'confirmed',
                'admin_status'              => 'approved',
                'is_postpone'               => false,
                'slot_date_after_postpone'  => null,
                'slot_time_after_postpone'  => null,
                'cancellation_reason'       => null,
                'created_at'                => now(),
                'updated_at'                => now(),
            ],
            [
                'jobseeker_id'               => 2,
                'user_type'                  => 'coach',
                'user_id'                    => 1,
                'booking_slot_id'            => 2,
                'slot_date'                  => Carbon::now()->addDays(3)->toDateString(),
                'slot_time'                  => '14:30:00 - 16:30:00',
                'status'                     => 'pending',
                'admin_status'              => 'pending',
                'is_postpone'               => false,
                'slot_date_after_postpone'  => null,
                'slot_time_after_postpone'  => null,
                'cancellation_reason'       => null,
                'created_at'                => now(),
                'updated_at'                => now(),
            ],
            [
                'jobseeker_id'               => 3,
                'user_type'                  => 'assessor',
                'user_id'                    => 4,
                'booking_slot_id'            => 3,
                'slot_date'                  => Carbon::now()->addDays(1)->toDateString(),
                'slot_time'                  => '16:00:00 - 18:00:00',
                'status'                     => 'cancelled',
                'admin_status'              => 'rejected',
                'is_postpone'               => true,
                'slot_date_after_postpone'  => Carbon::now()->addDays(4)->toDateString(),
                'slot_time_after_postpone'  => '17:00:00 - 19:00:00',
                'cancellation_reason'       => 'Rescheduled due to conflict',
                'created_at'                => now(),
                'updated_at'                => now(),
            ],
        ]);
    }
}
