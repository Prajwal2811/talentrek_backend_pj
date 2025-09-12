<?php

namespace App\Http\Controllers\API;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\SubscriptionPlan;
use App\Models\PurchasedSubscription;

use App\Models\Jobseekers;
use App\Models\Assessors;
use App\Models\Mentors;
use App\Models\Coach;
use App\Models\Trainers;

use DB;
use Carbon\Carbon;
class SubscriptionManagementController extends Controller
{
    public function subscriptionPlanListForMCAJT($type='mentor')
    {
    //    try {
            $SubscriptionPlan = SubscriptionPlan::select('*')->where('user_type',$type)->where('is_active',1)->get();
            if ($SubscriptionPlan->isEmpty()) {
                return $this->errorResponse('No Subscription list found for '.$type.' .', 200,[]);
            }
            return $this->successResponse($SubscriptionPlan, 'Subscription list for '.$type.' fetched successfully.');
        // } catch (\Exception $e) {
        //     return $this->errorResponse('An error occurred while fetching Subscription list for '.$type.' .', 500,[]);
        // }
    }

    public function processSubscriptionPaymentForMCAJT(Request $request)
    {
        
         $validator = Validator::make($request->all(), [
            'plan_id'     => 'required|exists:subscription_plans,id',
            'card_number' => 'required|string|min:12|max:19',
            'expiry'      => 'required',
            'user_id'     => 'required',
            'type'        => 'required|in:jobseeker,mentor,assessor,coach,trainer',
            'cvv'         => 'required|string|min:3|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        
      
        DB::beginTransaction();
        // try {
            $plan = SubscriptionPlan::findOrFail($request->plan_id);
           
            $map = [
                'mentor'    => 'mentors',
                'assessor'  => 'assessors',
                'jobseeker' => 'jobseeker',
                'trainer' => 'trainer',
            ];
            $type = $map[$request->type] ?? 'coach';
            $modelMap = [
                'jobseeker' => \App\Models\Jobseekers::class,
                'mentors' => \App\Models\Mentors::class,
                'assessors' => \App\Models\Assessors::class,
                'coach' => \App\Models\Coach::class,
                'trainer' => \App\Models\Trainers::class,
            ];
            $model = $modelMap[$type];
            $jobseeker = $model::where('id', $request->user_id)->first();
          
            // echo "done"; exit; 
            PurchasedSubscription::create([
                'user_id' => $jobseeker->id,
                'user_type' => $request->type,
                'subscription_plan_id' => $plan->id,
                'start_date' => now(),
                'end_date' => now()->addDays($plan->duration_days),
                'amount_paid' => $plan->price,
                //'status' => 'active',
                'payment_status' => 'paid',
            ]);
            // echo "done"; exit; 
            // $jobseeker->isSubscribtionBuy = 'yes';
            // $jobseeker->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Subscription purchased successfully!'
            ]);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Something went wrong while purchasing the subscription.'
        //     ], 500);
        // }
    }
}
