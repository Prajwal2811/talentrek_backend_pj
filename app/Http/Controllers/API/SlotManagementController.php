<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Api\BookingSession;
use App\Models\Api\BookingSlot;
use App\Models\Api\BookingSlotUnavailableDate;
use App\Models\Api\Assessors;
use App\Models\Api\Mentors;
use App\Models\Api\Coach;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SlotManagementController extends Controller
{
    public function createBookingSlotForMentorAssessorCoach(Request $request)
    {
        //print_r($request->all());exit;
        $validator = Validator::make($request->all(), [
            'slot' => 'required|array|min:1',
            'slot.*.start_time' => 'required|string',
            'slot.*.end_time' => 'required|string',
            'user_id' => 'required|integer',
            'slot_mode' => 'required|string',
            'user_type' => 'required|string'
        ]);
        

       if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $slotErrors = [];

        foreach ($request->slot as $index => $slot) {
            try {
                $startTime = Carbon::parse($slot['start_time']);
            } catch (\Exception $e) {
                $slotErrors["slot.$index.start_time"] = ['Invalid start time format.'];
                continue;
            }

            try {
                $endTime = Carbon::parse($slot['end_time']);
            } catch (\Exception $e) {
                $slotErrors["slot.$index.end_time"] = ['Invalid end time format.'];
                continue;
            }

            if ($endTime->lt($startTime)) {
                $slotErrors["slot.$index.end_time"] = ['End time must be after or equal to start time.'];
            }

            // if ($startTime->gt(now())) {
            //     $slotErrors["slot.$index.start_time"] = ['Start time cannot be in the future.'];
            // }

            // Check if the slot already exists
            $exists = BookingSlot::where('user_id', $request->user_id)
                ->where('user_type', $request->user_type)
                ->where('slot_mode', $request->slot_mode)
                ->where('start_time', $startTime->format('H:i:s'))
                ->where('end_time', $endTime->format('H:i:s'))
                ->get();

             if ($exists->count() > 0) {
                return response()->json(['status'  => false,'errors' => 'Some of the slots already exist in your sessions please check.'], 200);               
            }
        }

        if (!empty($slotErrors)) {
            return response()->json(['status'  => false,'errors' => 'Please check your session time formates.'], 200);
        }
        if ($validator->errors()->any()) {          
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first(),
                ], 422);            
        }


        try {
            foreach ($request->slot as $slot) {
                $startTime = Carbon::parse($slot['start_time'])->format('H:i:s');
                $endTime   = Carbon::parse($slot['end_time'])->format('H:i:s');

                BookingSlot::create([
                    'user_id'    => $request->user_id,
                    'user_type'  => $request->user_type,
                    'slot_mode'  => $request->slot_mode,
                    'start_time' => $startTime,
                    'end_time'   => $endTime,
                ]);
            }
            return response()->json([
                'status'  => TRUE,
                'message' => ucwords($request->user_type).' boking slots are added successfully.'                
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function markBookingSlotDateUnavailableForMCA(Request $request)
    {
        $data = $request->all();

        $rules = [
            'user_id'        => 'required|integer',
            'user_type'      => 'required|string',
        ];

        
        $rules["unavailbaleDate"] = [
            'required',
            'date_format:d/m/Y',
            function ($attribute, $value, $fail) {
                try {
                    $date = Carbon::createFromFormat('d/m/Y', $value);                    
                    $today = Carbon::today();

                    if ($date < $today) {
                        $fail("The unavailable date must not be in the past.");
                    }
                } catch (\Exception $e) {
                    $fail("The unavailable date must be a valid date in d/m/Y format.");
                }
            },
        ]; 
        $validator = Validator::make($data, $rules);

        // Return only the first error
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }        
        

        try {
                $userId = $request->user_id;
                $userType = $request->user_type;
                $dateAdd = Carbon::createFromFormat('d/m/Y',$request->unavailbaleDate)->format('Y-m-d');

                // Get all booking slots for this user
                $slots = BookingSlot::where('user_id', $userId)
                            ->where('user_type', $userType)
                            ->get();

                if ($slots->isEmpty()) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'No booking slots found for the given user.'
                    ], 404);
                }

                foreach ($slots as $slot) {
                    $existing = BookingSlotUnavailableDate::where('unavailable_date', $dateAdd)
                        ->where('booking_slot_id', $slot->id)
                        ->first();

                    if ($existing) {
                        // If exists, delete (unmark)
                        $existing->delete();
                    } else {
                        // If not exists, insert (mark as unavailable)
                        BookingSlotUnavailableDate::create([
                            'booking_slot_id'   => $slot->id,
                            'unavailable_date'  => $dateAdd
                        ]);
                    }
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Slots marked as unavailable for the selected date.'
                ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function showBookingSlotDetailsByDateForMCA(Request $request)
    {
        //print_r($request->all());exit;
        // $validator = Validator::make($request->all(), [
        //     'user_id'    => 'required|integer',
        //     'searchDate' => 'required|date_format:d/m/Y',
        //     'user_type'  => 'required|string',
        // ]);
        $data = $request->all();

        $rules = [
            'user_id'        => 'required|integer',
            'user_type'      => 'required|string',
        ];

        
        $rules["searchDate"] = [
            'required',
            'date_format:d/m/Y',
            function ($attribute, $value, $fail) {
                try {
                    $date = Carbon::createFromFormat('d/m/Y', $value);                    
                    $today = Carbon::today();

                    if ($date < $today) {
                        $fail("The unavailable date must not be in the past.");
                    }
                } catch (\Exception $e) {
                    $fail("The unavailable date must be a valid date in d/m/Y format.");
                }
            },
        ]; 
        $validator = Validator::make($data, $rules);

        // Return only the first error
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }       

        try {
           
                $userId = $request->user_id;
                $userType = $request->user_type;
                $date = Carbon::createFromFormat('d/m/Y', $request->searchDate)->format('Y-m-d');

                // Get all booking slots for this user
                $slots = BookingSlot::select('booking_slots.id',
                    'booking_slots.user_id',
                    'booking_slots.user_type',
                    'booking_slots.slot_mode',
                    'booking_slots.start_time',
                    'booking_slots.end_time',
                    DB::raw("DATE_FORMAT(talentrek_booking_slots.start_time, '%h:%i %p') as startTime"),
                    DB::raw("DATE_FORMAT(talentrek_booking_slots.end_time, '%h:%i %p') as endTime"),
                   
                )                
                ->where('booking_slots.user_id', $userId)
                ->where('booking_slots.user_type', $userType)
                ->get()->map(function ($slot) use ($date) {
                    $isUnavailable = DB::table('booking_slots_unavailable_dates')
                        ->where('booking_slot_id', $slot->id)
                        ->where('unavailable_date', $date)
                        ->exists();

                    $isBooked = DB::table('jobseeker_saved_booking_session')
                        ->where('booking_slot_id', $slot->id)
                        ->where('slot_date', $date)
                        ->where('status', 'pending')
                        ->exists();

                    $slot->is_unavailable = !$isUnavailable;
                    $slot->is_booked = $isBooked;
                    return $slot;
                });

                if ($slots->isEmpty()) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'No booking slots found for the given user.'
                    ], 404);
                }                

                $slotPrice = $this->getUserSlotPrice($userId,$userType);
                $slotTax = $this->getSlotPercentage($userType) ;
                $slotTotalAmount = $slotPrice + ($slotPrice * $slotTax / 100); 
                return response()->json([
                    'status'  => true,
                    'perSlotPrice' => $slotPrice, 
                    'slotTaxPercentage' => $slotTax, 
                    'slotTotalAmount' => $slotTotalAmount, 
                    'data' => $slots 
                ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function deleteBookingSlotDetailsByIdForMCA($bookingSlotId)
    {
        try {
                $id = $bookingSlotId;
                 $slot = BookingSlot::find($id);

                if (!$slot) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Booking slot not found.'
                    ], 200);
                }

                // Delete the slot
                $slot->delete();

                // Optionally also delete related unavailable dates (if needed)
                BookingSlotUnavailableDate::where('booking_slot_id', $id)->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Booking slot deleted successfully.'
                ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function updateBookingSlotDetailsByIdForMCA(Request $request)
    {
        try {
                $validator = Validator::make($request->all(), [
                    'id'         => 'required|exists:booking_slots,id',
                    'start_time' => 'required|date_format:H:i',
                    'end_time'   => 'required|date_format:H:i|after:start_time',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status'  => false,
                        'message' => $validator->errors()->first(),
                    ], 422);
                }

                // Convert to 24-hour format
               // $startTime = Carbon::createFromFormat('h:i a', $request->start_time)->format('H:i:s');
               // $endTime = Carbon::createFromFormat('h:i a', $request->end_time)->format('H:i:s');

                $startTime = Carbon::parse($request->start_time)->format('H:i:s');
                $endTime   = Carbon::parse($request->end_time)->format('H:i:s');

                $slot = BookingSlot::findOrFail($request->id);
                $slot->update([
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Slot time updated successfully.',
                    'slot' => $slot,
                ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function markSlotUnavailableByDateForMCA(Request $request)
    {
        $data = $request->all();

        $rules = [
            'id'       => 'required|exists:booking_slots,id',
        ];
        
        $rules["markDate"] = [
            'required',
            'date_format:d/m/Y',
            function ($attribute, $value, $fail) {
                try {
                    $date = Carbon::createFromFormat('d/m/Y', $value);                    
                    $today = Carbon::today();

                    if ($date < $today) {
                        $fail("The mark date must not be in the past.");
                    }
                } catch (\Exception $e) {
                    $fail("The mark date must be a valid date in d/m/Y format.");
                }
            },
        ]; 
        $validator = Validator::make($data, $rules);

        // Return only the first error
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }   

        try {
                $date = Carbon::createFromFormat('d/m/Y', $request->markDate)->format('Y-m-d');
                $existing = BookingSlotUnavailableDate::where('unavailable_date', $date)->where('booking_slot_id', $request->id)->first();
                if ($existing) {
                    // If exists, delete (unmark)
                    $existing->delete();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Slot time available for '.$request->markDate
                    ]);
                }

                // If not exists, insert (mark as unavailable)
                BookingSlotUnavailableDate::create([
                    'booking_slot_id'   => $request->id,
                    'unavailable_date'  => $date
                ]);                    

                return response()->json([
                    'status' => 'success',
                    'message' => 'Slot time unavailable for '.$request->markDate
                ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function cancelSessionByRoleForMCA(Request $request)
    {
        try {
               $validator = Validator::make($request->all(), [
                    'id'     => 'required',
                    'reason' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status'  => false,
                        'message' => $validator->errors()->first(),
                    ], 422);
                }

                $slot = BookingSession::findOrFail($request->id);
                $slot->update([
                    'status' => 'cancelled',
                    'cancellation_reason' => $request->reason,
                ]);                    

                return response()->json([
                    'status' => 'success',
                    'message' => 'Booking session cancelled successfully .'
                ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function rescheduleSessionByRoleForMCA(Request $request)
    {
        $data = $request->all();

        $rules = [
            'id'          => 'required',
            'reason'      => 'required|string',
            'slot_id'     => 'required',
        ];

        
        $rules["sessionDate"] = [
            'required',
            'date_format:d/m/Y',
            function ($attribute, $value, $fail) {
                try {
                    $date = Carbon::createFromFormat('d/m/Y', $value);                    
                    $today = Carbon::today();

                    if ($date < $today) {
                        $fail("The  date must not be in the past.");
                    }
                } catch (\Exception $e) {
                    $fail("The  date must be a valid date in d/m/Y format.");
                }
            },
        ]; 
        $validator = Validator::make($data, $rules);

        // Return only the first error
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }   

        try {
                 $date = Carbon::createFromFormat('d/m/Y', $request->sessionDate)->format('Y-m-d');

                $slot = BookingSession::findOrFail($request->id);

                // Check for availability
                $isTaken = BookingSession::where('booking_slot_id', $request->slot_id)
                    ->where('slot_date', $date)
                    ->whereNotIn('status', ['cancelled', 'postpond']) // ignore cancelled/postponed sessions
                    ->exists();

                if ($isTaken) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Selected slot is already booked for the given date.'
                    ], 200);
                }

                // Postpone original session
                $slot->update([
                    'status' => 'postpond',
                    'cancellation_reason' => $request->reason,
                    'is_postpone' => 1,
                    'slot_date_after_postpone' => $date,
                ]);

                // Create new session
                BookingSession::create([
                    'user_id'         => $slot->user_id,
                    'user_type'       => $slot->user_type,
                    'slot_mode'       => $slot->slot_mode,
                    'jobseeker_id'    => $slot->jobseeker_id,
                    'booking_slot_id' => $request->slot_id,
                    'slot_time' => $slot->slot_time,
                    'slot_date'       => $date,
                    'status'          => 'pending',
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Session postponed and new session booked successfully.'
                ]);                

                

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function calenderUnavailableDatesForMCA(Request $request)
    {
            try{
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required',
                    'type'    => 'required|string',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status'  => false,
                        'message' => $validator->errors()->first(),
                    ], 422);
                }

       
               $unavailableDates = BookingSlot::with('unavailableDates')
                ->where('user_id', $request->user_id)
                ->where('user_type', $request->type)
                ->get()
                ->pluck('unavailableDates.*.unavailable_date')
                ->flatten()
                ->unique()
                ->values();

                return response()->json([
                    'status' => true,
                    'message' => 'Un Available dates for slots in calender.',
                    'data' => $unavailableDates
                ]);                

                

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    private function getUserSlotPrice($id,$type)
    {
        if ($type == 'mentor') 
        {
            $MentorsDetails = Mentors::select('per_slot_price')->where('id', $id)->first();
            return $MentorsDetails->per_slot_price ;
        } 
        elseif ($type == 'coach') 
        {
            $MentorsDetails = Coach::select('per_slot_price')->where('id', $id)->first();
            return $MentorsDetails->per_slot_price ;
        }
        elseif ($type == 'assessor') 
        {
            $MentorsDetails = Assessors::select('per_slot_price')->where('id', $id)->first();
            return $MentorsDetails->per_slot_price ;
        }
       
        return 1 ;
    }

    private function getSlotPercentage($type)
    {
        if ($type == 'mentor') 
        {
            $MentorsDetails = Setting::select('mentorTax')->where('id', 1)->first();
            return $MentorsDetails->mentorTax ;
        } 
        elseif ($type == 'coach') 
        {
            $MentorsDetails = Setting::select('coachTax')->where('id', 1)->first();
            return $MentorsDetails->coachTax ;
        }
        elseif ($type == 'assessor') 
        {
            $MentorsDetails = Setting::select('assessorTax')->where('id', 1)->first();
            return $MentorsDetails->assessorTax ;
        }
       
        return 1 ;
    }
}
