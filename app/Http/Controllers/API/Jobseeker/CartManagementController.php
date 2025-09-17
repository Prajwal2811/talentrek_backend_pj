<?php

namespace App\Http\Controllers\API\Jobseeker;
use App\Models\Api\CMS;
use App\Models\Setting;
use App\Models\Api\Mentors;
use App\Models\Api\Testimonial;
use App\Models\Api\TrainingMaterial;
use App\Models\Api\TrainingPrograms;
use App\Models\Api\Trainers;
use App\Models\Api\Coupon;

use App\Models\Api\JobseekerTrainingMaterialPurchase;
use App\Models\Payment\JobseekerTrainingMaterialPurchasePaymentRequest;
use App\Services\PaymentHelper;

use App\Models\Api\JobseekerCartItem;
use App\Models\Api\CorporatesEmailIds;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class CartManagementController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return view('home');
    }

    public function addToCartByJobseeker(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'training_material_id' => 'required|integer',
                'jobseekerId'          => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $id = $request->training_material_id;
            $jobseekerId = $request->jobseekerId;

            $material = TrainingMaterial::find($id);

            if (!$material) {
                return response()->json(['status' => false, 'message' => 'Invalid material ID.'], 200);
            }

            $exists = JobseekerCartItem::where('jobseeker_id', $jobseekerId)
                ->where('material_id', $id)
                ->exists();

            if ($exists) {
                return response()->json(['status' => false, 'message' => 'Item is already in your cart.'], 200);
            }

            JobseekerCartItem::create([
                'jobseeker_id' => $jobseekerId,
                'trainer_id' => $material->trainer_id,
                'material_id' => $id,
                'status' => 'pending',
            ]);

            return response()->json(['status' => true, 'message' => 'Item added to cart successfully.']);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


  public function removeCartItemByJobseeker(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'jobseekerId'          => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $id = $request->id;
            $jobseekerId = $request->jobseekerId;

            $item = JobseekerCartItem::where('id', $id)
                ->where('jobseeker_id', $jobseekerId)
                ->first();

            if (!$item) {
                return response()->json(['status' => false, 'message' => 'Item not found'], 404);
            }

            $item->delete();

            return response()->json(['status' => true, 'message' => 'Item removed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    public function viewCartItemByJobseeker($jobseekerId)
    {
        try {
            $items = JobseekerCartItem::with([
                'trainer' => function ($query) {
                    $query->select('id', 'name', 'email'); // Customize fields as needed
                },
                'material' => function ($query) {
                    $query->select('id', 'training_title','training_type', 'training_descriptions','thumbnail_file_path as image','training_price','training_offer_price'); // Customize fields as needed
                }                
            ])
            ->withAvg('trainerReviews','ratings')
            ->with('latestWorkExperience')
            ->where('jobseeker_id', $jobseekerId)
            ->where('status', 'pending')
            ->get()->map(function ($item) {
                $material = $item->material;
                $trainer = $item->trainer;
                $latestWorkExperience = $item->latestWorkExperience;
                $item->trainerName = $trainer->name;
                $item->trainerEmail = $trainer->email;

                $item->training_title = $material->training_title;
                $item->training_type = $material->training_type;
                $item->training_descriptions = $material->training_descriptions;
                $item->image = $material->image;
                $item->training_price = $material->training_price;
                $item->training_offer_price = $material->training_offer_price;
                $item->designation = $latestWorkExperience->job_role ?? 'N/A';
                
                $item->actual_price = (!empty($material->training_price))
                    ? $material->training_price
                    : $material->training_offer_price;

                $item->final_price = (!empty($material->training_offer_price))
                    ? $material->training_offer_price
                    : $material->training_price;
                // ✅ Add trainer average rating
                $item->trainer_avg_rating = round($item->trainer_reviews_avg_ratings, 1);
                unset($item->material, $item->trainer,$item->latestWorkExperience);
                return $item;
            });

            // 💰 Get sum of final_price
            $actualPrice = $items->sum('actual_price');
            $courseTotalPrice = $items->sum('final_price');
            $savedPrice = $actualPrice - $courseTotalPrice;
            $gstTax = $this->getSlotPercentage('material') ;
            $totalPrice = number_format($courseTotalPrice + ($courseTotalPrice * $gstTax / 100), 2, '.', '');

            if ($items->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No items found in cart.'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Item list retrieved successfully.','courseTotalPrice' => $courseTotalPrice,'savedPrice' => $savedPrice,'gstTax' => $gstTax,'totalPrice' => $totalPrice, 'data' => $items]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function butNowSlotForMCA(Request $request)
    {
        $data = $request->all();

        $rules = [
            'slotId' => 'required|integer',
            'jobseekerId'  => 'required|integer',
            'slotDate' => 'required',
            'user_type' => 'required',
            'user_id' => 'required|integer',
        ];

        $rules["slotDate"] = [
            'required',
            'date_format:d/m/Y',
            function ($attribute, $value, $fail) {
                try {
                    $date = Carbon::createFromFormat('d/m/Y', $value);                    
                    $today = Carbon::today();

                    if ($date < $today) {
                        $fail("The date must not be in the past.");
                    }
                } catch (\Exception $e) {
                    $fail("The date must be a valid date in d/m/Y format.");
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
            
            //$id = $request->training_material_id;
            $jobseekerId = $request->jobseekerId;

            
            $slotDate = Carbon::createFromFormat('d/m/Y', $request->slotDate)->format('Y-m-d') ;
            $exists = BookingSession::where('booking_slot_id', $request->slotId)
                ->where('slot_date', $slotDate)
                ->where('status', 'pending')
                ->exists();

            if ($exists) {
                return response()->json(['status' => false, 'message' => 'This slot already booked by someone for this date.'], 200);
            }

            BookingSession::create([
                'jobseeker_id' => $jobseekerId,
                'booking_slot_id' => $request->slotId,
                'user_id' => $request->user_id,
                'user_type' => $request->user_type,
                'slot_date' => $slotDate,
                'status' => 'pending',
                'slot_time' => '555',
            ]);

            return response()->json(['status' => true, 'message' => $request->user_type.' session booked successfully.']);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function butNowTrainingMaterials(Request $request)
    {
        $data = $request->all();

        // $rules = [
        //     'material_id' => 'required|integer',
        //     'jobseekerId'  => 'required|integer',
        //     'trainer_id' => 'required',
        //     'training_type' => 'required|in:online,classroom,recorded',
        //     'session_type' => 'required_if:training_type,online|in:online,classroom,recorded',
        //     'batch' => 'required_if:training_type,online|exists:training_batches,id',
        //     'payment_method' => 'required|in:card,upi'
            
        // ];        
        // $validator = Validator::make($data, $rules);

        // // Return only the first error
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => $validator->errors()->first()
        //     ], 200);
        // }    

        $rules = [
            'material_id'    => 'required|integer',
            'jobseekerId'    => 'required|integer',
            'trainer_id'     => 'required',
            'training_type'  => 'required|in:online,classroom,recorded',
            'payment_method' => 'required|in:card,upi',
        ];

        $validator = Validator::make($request->all(), $rules);

        // ✅ Apply batch validation only if training_type = online
        $validator->sometimes('batch', 'required|exists:training_batches,id', function ($input) {
            return in_array($input->training_type, ['online', 'classroom']);
        });
         $validator->sometimes('session_type', 'required', function ($input) {
            return in_array($input->training_type, ['online', 'classroom']);
        });

        if ($validator->fails()) {
            return response()->json([ 'status' => false,'errors' => $validator->errors()->first()], 200);
        }

        try {
            
            //$id = $request->training_material_id;
            $jobseekerId = $request->jobseekerId;

            
           $exists = JobseekerTrainingMaterialPurchase::where('material_id', $request->material_id)
                ->where('jobseeker_id', $request->jobseekerId)
                ->where('trainer_id', $request->trainer_id)                
                ->exists();

            if ($exists) {
                return response()->json(['status' => false, 'message' => 'This material already purchased.'], 200);
            }

            $material = TrainingMaterial::with('batches')->findOrFail($request->material_id);

            $actualPrice = $material->training_price;
            $offerPrice = $material->training_offer_price;
            $savedAmount = $actualPrice - $offerPrice;
            $tax = round($offerPrice * 0.10, 2);
            $total = $offerPrice + $tax;

            JobseekerTrainingMaterialPurchase::create([
                'jobseeker_id' => $jobseekerId,
                'trainer_id' => $material->trainer_id,
                'material_id' => $material->id,
                'training_type' => $request->training_type,
                'session_type' => $request->session_type,
                'batch_id' => $request->batch,
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'tax' => $tax,
                'discount' => $savedAmount,
                'status' => 'paid',
            ]);

            return response()->json(['status' => true, 'message' => $request->user_type.' material purchased successfully.']);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showTrainingMaterialsBatches($id,$jobseekerId)
    {      
        $material = DB::table('training_materials')->where('id', $id)->first();
        if (!$material) {
            abort(404, 'Course not found');
        }

        $actualPrice = (!empty($material->training_price))
            ? $material->training_price
            : $material->training_offer_price;

        $courseTotalPrice = (!empty($material->training_offer_price))
            ? $material->training_offer_price
            : $material->training_price;
            
        $savedPrice = $actualPrice - $courseTotalPrice;
        $gstTax = $this->getSlotPercentage('batch') ;
        $totalPrice = number_format($courseTotalPrice + ($courseTotalPrice * $gstTax / 100), 2, '.', '');

        
        $material->documents = DB::table('training_materials_documents')
            ->where('training_material_id', $material->id)
            ->get();

        $material->batches = DB::table('training_batches')
            ->where('training_material_id', $material->id)
            ->get()->map(function ($batch) use ($jobseekerId) {

            // ✅ 1. Check if user already purchased
            $alreadyPurchased = DB::table('jobseeker_training_material_purchases')
                ->where('batch_id', $batch->id)
                ->where('jobseeker_id', $jobseekerId)
                ->exists();

            // ✅ 2. Check if batch is full
            $buyersCount = DB::table('jobseeker_training_material_purchases')
                ->where('batch_id', $batch->id)
                ->count();

            $isFull = $buyersCount >= $batch->strength; // strength = max capacity

            // ✅ 3. Check if expired
            $isExpired = Carbon::now()->gt(Carbon::parse($batch->end_date));

            // ✅ 4. Final Status
            if ($alreadyPurchased) {
                $batch->status = "Already Purchased";
                $batch->available = false;
            } elseif ($isFull) {
                $batch->status = "Batch Full";
                $batch->available = false;
            } elseif ($isExpired) {
                $batch->status = "Expired";
                $batch->available = false;
            } else {
                $batch->status = "Available";
                $batch->available = true;
            }

            return $batch;
        });

        $userType = null;
        $userId = null;
        $user = null;

        // Detect user type and get basic info
        if (!empty($material->trainer_id)) {
            $userType = 'trainer';
            $userId = $material->trainer_id;
            $user = DB::table('trainers')->where('id', $userId)->first();
        } elseif (!empty($material->mentor_id)) {
            $userType = 'mentor';
            $userId = $material->mentor_id;
            $user = DB::table('mentors')->where('id', $userId)->first();
        } elseif (!empty($material->coach_id)) {
            $userType = 'coach';
            $userId = $material->coach_id;
            $user = DB::table('coaches')->where('id', $userId)->first();
        } elseif (!empty($material->assessor_id)) {
            $userType = 'assessor';
            $userId = $material->assessor_id;
            $user = DB::table('assessors')->where('id', $userId)->first();
        }

        if (!$userType || !$userId || !$user) {
            abort(404, 'User info not found');
        }

        // Fetch profile picture from talentrek_additional_info
        $profile = DB::table('additional_info')
            ->where('user_id', $userId)
            ->where('user_type', 'trainer')
            ->where('doc_type', 'trainer_profile_picture')
            ->orderByDesc('id')
            ->first();

        $material->user_name = $user->name ?? '';
        $material->user_profile = $profile->document_path ?? asset('asset/images/avatar.png');

        // Ratings and reviews
        $total = DB::table('reviews')
            ->where('user_type', $userType)
            ->where('user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('trainer_material', $material->id);
            })
            ->count();

        $average = $total > 0
            ? round(DB::table('reviews')
                ->where('user_type', $userType)
                ->where('user_id', $userId)
                ->when($userType === 'trainer', function ($q) use ($material) {
                    $q->where('trainer_material', $material->id);
                })
                ->avg('ratings'), 1)
            : 0;

        $ratings = DB::table('reviews')
            ->select('ratings', DB::raw('COUNT(*) as count'))
            ->where('user_type', $userType)
            ->where('user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('trainer_material', $material->id);
            })
            ->groupBy('ratings')
            ->pluck('count', 'ratings');

        $ratingsPercent = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $ratings[$i] ?? 0;
            $ratingsPercent[$i] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
        }

        $reviews = DB::table('reviews as r')
            ->join('jobseekers as j', 'r.jobseeker_id', '=', 'j.id')
            ->select('r.*', 'j.name as jobseeker_name')
            ->where('r.user_type', $userType)
            ->where('r.user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('r.trainer_material', $material->id);
            })
            ->latest('r.created_at')
            ->limit(10)
            ->get();

        return response()->json(['status' => true, 'message' => 'material batches fetched  successfully.','courseTotalPrice' => $courseTotalPrice,'savedPrice' => $savedPrice,'gstTax' => $gstTax,'totalPrice' => $totalPrice,'data' =>[
            'material'       => $material,
            'user'           => $user,
            'userType'       => $userType,
            'userId'         => $userId,
            'average'        => $average,
            'ratingsPercent' => $ratingsPercent,
            'reviews'        => $reviews,
        ]]);
        
    }

   
    public function checkoutTrainingMaterials(Request $request)
    {
        $data = $request->all();
        $rules = [
           'jobseekerId'    => 'required|integer',
            'buy_type'  => 'required|in:buyNow,buyForCorporates,cart'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        // ✅ Apply validation only if buyTtpe != cart
        $validator->sometimes('material_id', 'required', function ($input) {
            return in_array($input->training_type, ['buyNow', 'buyForCorporates']);
        });

         $validator->sometimes('trainer_id', 'required', function ($input) {
            return in_array($input->training_type, ['buyNow', 'buyForCorporates']);
        });

        $validator->sometimes('training_type', 'required', function ($input) {
            return in_array($input->training_type, ['buyNow', 'buyForCorporates']);
        });

        // ✅ Apply batch validation only if training_type = online
        $validator->sometimes('batch', 'required|exists:training_batches,id', function ($input) {
            return in_array($input->training_type, ['online', 'classroom']);
        });
        $validator->sometimes('session_type', 'required', function ($input) {
            return in_array($input->training_type, ['online', 'classroom']);
        });
        $validator->sometimes('corporatesEmailId', 'required', function ($input) {
            return in_array($input->buy_type, ['buyForCorporates']);
        });

        if ($validator->fails()) {
            return response()->json([ 'status' => false,'errors' => $validator->errors()->first()], 200);
        }

        //try {
            
            //$id = $request->training_material_id;
            $jobseekerId = $request->jobseekerId;            
            $buyType     = $request->buy_type;
            if($buyType == 'buyNow'){
                $exists = JobseekerTrainingMaterialPurchase::where('material_id', $request->material_id)
                    ->where('jobseeker_id', $request->jobseekerId)
                    ->where('trainer_id', $request->trainer_id)                
                    ->exists();
    
                if ($exists) {
                    return response()->json(['status' => false, 'message' => 'This material already purchased.'], 200);
                }
            }           


            $material = null;
            if ($buyType !== 'cart') {
                $material = TrainingMaterial::with('batches')->findOrFail($request->material_id);
            }
           
            // Calculate prices
            if ($buyType === 'cart') {
                $cartItems = JobseekerCartItem::with('material')
                    ->where('jobseeker_id', $jobseekerId)
                    ->where('status', 'pending')
                    ->get();

                $actualPrice = $cartItems->sum(fn($item) => $item->material->training_price ?? 0);
                $offerPrice  = $cartItems->sum(fn($item) => $item->material->training_offer_price ?? 0);
            } else {
                $actualPrice = $material->training_price;
                $offerPrice  = $material->training_offer_price;
            }

            $savedAmount = $actualPrice - $offerPrice;
            $tax         = round($offerPrice * 0.10, 2);
            $total       = $offerPrice + $tax;

            $payload = '' ;
            $booking = JobseekerTrainingMaterialPurchasePaymentRequest::create([
                'jobseeker_id'     => $jobseekerId,
                'trainer_id'       => $request->trainer_id ?? 0,
                'material_id'      => $request->material_id ?? 0,
                'request_payload'  => !empty($payload) ? json_encode($payload) : null,
                'track_id'         => 'Trk-'.time(), // custom helper
                'training_type'    => $request->training_type ?? 'recorded', // e.g. online/offline/self-paced
                'batch_id'         => $request->batch ?? 0, //new
                'type'             => $request->buy_type, // buyNow | buyForCorporate | cart
                'transaction_id'   => null,
                'payment_status'   => 'initiated',
                'tax'              => $tax ?? 0.00,
                'amount'           => $offerPrice ?? 0.00,
                'amount_paid'      => $total ?? 0.00,
                'currency'         => 'SAR',
                'payment_gateway'  => 'Al Rajhi',
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ]);
            $trackId =  self::generateTrackId($jobseekerId, $request->trainer_id, $request->material_id, $request->batch , $request->buy_type) ;
            $referenceNo =  "TRK-" .  $trackId ;
            if($buyType == 'buyForCorporates'){
                $CorporatesEmailIds = CorporatesEmailIds::create([
                    'corporatesEmailIds' => $request->corporatesEmailId,
                    'paymentRequestId'  =>  $booking->id,
                    'track_id'  =>  $referenceNo,
                ]);
            }
            $booking->update(['track_id' => $referenceNo]);

            $config = config('neoleap');
            $transactionDetails = [
                "id"          => $config['tranportal_id'], // Your Merchant ID from Al Rajhi
                "amt"         => number_format($total, 2, '.', ''),          // Transaction amount
                "action"      => "1",               // 1 = Purchase, 4 = Authorization
                "password"    => "T4#2H#ma5yHv\$G7",
                "currencyCode"=> "682",             // ISO numeric code (682 = SAR)
                "trackId"     => "TRK-" .  $trackId ,          // Unique tracking ID
                "udf1"        => $jobseekerId,     // Jobseeker Id
                "udf2"        => $request->buy_type,        // Material Purchase type buyNw,butForCorporates,Cart
                "udf3"        => $request->batch ?? 0,          // Batch ID
                "udf4"        => $request->training_type ?? 'recorded',         // TrainingType online ,classroom ,recoded 
                "udf5"        => $request->trainingMode,    // Online/Classroom
                "udf6"        => $request->material_id,              // Material Id
                "udf7"        => $tax ?? 0.00,              // Mentor Session Tax
                "udf8"        => $offerPrice,              // Mentor session Slot Price
                "udf9"        => $CorporatesEmailIds->id ?? '',              // Mentor session Slot Price
                "udf10"       => $request->trainer_id,              // Mentor session Slot Price
                "langid"      => "en",                      // change to ar when goes live for arabic default
                "responseURL" => $config['success_material_purchase_url'],
                "errorURL"    => $config['success_material_purchase_url']
            ];
            $payload = [$transactionDetails];

            $jsonTrandata = json_encode($payload, JSON_UNESCAPED_SLASHES);
        
            $trandata = PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']);
            $trandatas = strtoupper($trandata) ;
            $booking->update(['request_payload' => $jsonTrandata]);
            $payload = [[
                "id"          => $config['tranportal_id'],
                "trandata"    => $trandatas, // ✅ variable here
                "responseURL" => $config['success_material_purchase_url'],
                "errorURL"    => $config['success_material_purchase_url']
            ]];
            $payloads = json_encode($payload, JSON_UNESCAPED_SLASHES) ;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://securepayments.neoleap.com.sa/pg/payment/hosted.htm',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $payloads,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            //echo $response;exit;
            // Decode JSON
            $data = json_decode($response, true);

            // Get the "result" value
            $result = $data[0]['result'];
            if (!$result || !str_contains($result, ':')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Payment gateway error.',
                ], 500);
            }
            // Split by colon
            list($paymentId, $paymentUrl) = explode(":", $result, 2);

            
            // Final redirect URL
            $redirectUrl = $paymentUrl . "?PaymentID=" . $paymentId;
           
            // Return success with data
            return response()->json([
                'success' => true,
                'redirectUrl' => $redirectUrl,
                'message' => ucwords($request->buy_type).'  material purchase checkout successfully.'
            ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Something went wrong.',
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }

    public function applyCouponForBuyNowCourese(Request $request)
    {
        $data = $request->all();
        $rules = [
            'material_id' => 'required|integer|exists:training_materials,id',
            'couponCode'  => 'required|exists:coupons,code'
        ];

        $messages = [
            'material_id.required' => 'Material ID is required.',
            'material_id.integer'  => 'Material ID must be a valid number.',
            'material_id.exists'   => 'Training material does not exist.',
            
            'couponCode.required'  => 'Coupon code is required.',
            'couponCode.exists'    => 'Coupon code does not exist.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return response()->json([ 'status' => false,'message' => $validator->errors()->first()], 200);
        }

        try {
            
            //$id = $request->training_material_id;
            $material_id = $request->material_id;            
            $couponCode     = $request->couponCode;
            
            $material = null;
          
            $material = TrainingMaterial::with('batches')->findOrFail($material_id);         
           
            // Calculate prices            
            $actualPrice = $material->training_price;
            $offerPrice  = $material->training_offer_price;            
            // Default applied price = offerPrice
            $finalPrice = $offerPrice;
            
            // ========================
            // Fetch & validate coupon
            // ========================
            $coupon = Coupon::where('code', $couponCode)
                ->where('is_active', 1)
                ->whereDate('valid_from', '<=', now())
                ->whereDate('valid_to', '>=', now())
                ->first();

            if (!$coupon) {
                return response()->json([
                    'status'  => false,
                    'message' => $couponCode.'innvalid or expired.'
                ], 200);
            }

            // ========================
            // Apply discount
            // ========================
            $discountAmount = 0;

            if ($coupon->discount_type === 'percentage') {
                $discountAmount = ($offerPrice * $coupon->discount_value) / 100;
            } elseif ($coupon->discount_type === 'fixed') {
                $discountAmount = $coupon->discount_value;
            }

            // Prevent discount from exceeding price
            if ($discountAmount > $offerPrice) {
                $discountAmount = $offerPrice;
            }

            $finalPrice = $offerPrice - $discountAmount;

            // Tax (10% as per your code)
            $tax   = round($finalPrice * 0.10, 2);
            $total = $finalPrice + $tax;

            // ========================
            // Return response
            // ========================
            return response()->json([
                'status'         => true,
                'message'        => $coupon->code.' coupon applied.',
                'material_id'    => $material->id,
                'coupon_code'    => $coupon->code,
                'discount_type'  => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
                'actual_price'   => $actualPrice,
                'offer_price'    => $offerPrice,
                'discount_amount'=> $discountAmount,
                'final_price'    => $finalPrice,
                'taxPercentage'            => 10.00,
                'tax'            => $tax,
                'total'          => $total
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function applyCouponForBuyNowCorporatesCoures(Request $request)
    {
        $rules = [
            'material_id'  => 'required|integer|exists:training_materials,id',
            'couponCode'   => 'required|exists:coupons,code',
            'numberOfUser' => 'required|integer|min:1'
        ];

        $messages = [
            'material_id.required' => 'Material ID is required.',
            'material_id.integer'  => 'Material ID must be a valid number.',
            'material_id.exists'   => 'Training material does not exist.',
            
            'couponCode.required'  => 'Coupon code is required.',
            'couponCode.exists'    => 'Coupon code does not exist.',

            'numberOfUser.required' => 'Number of users is required.',
            'numberOfUser.integer'  => 'Number of users must be a valid number.',
            'numberOfUser.min'      => 'Number of users must be at least 1.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        try {
            $material_id   = $request->material_id;            
            $couponCode    = $request->couponCode;
            $numberOfUser  = (int) $request->numberOfUser;

            $material = TrainingMaterial::with('batches')->findOrFail($material_id);         

            // Base prices
            $actualPrice = $material->training_price;
            $offerPrice  = $material->training_offer_price ?? $actualPrice;

            // Total before discount
            $totalOfferPrice = $offerPrice * $numberOfUser;

            // ========================
            // Fetch & validate coupon
            // ========================
            $coupon = Coupon::where('code', $couponCode)
                ->where('is_active', 1)
                ->whereDate('valid_from', '<=', now())
                ->whereDate('valid_to', '>=', now())
                ->first();

            if (!$coupon) {
                return response()->json([
                    'status'  => false,
                    'message' => $couponCode.' is invalid or expired.'
                ], 200);
            }

            // ========================
            // Apply discount
            // ========================
            $discountAmount = 0;

            if ($coupon->discount_type === 'percentage') {
                $discountAmount = ($totalOfferPrice * $coupon->discount_value) / 100;
            } elseif ($coupon->discount_type === 'fixed') {
                $discountAmount = $coupon->discount_value;
            }

            // Prevent discount from exceeding total
            if ($discountAmount > $totalOfferPrice) {
                $discountAmount = $totalOfferPrice;
            }

            $finalPrice = $totalOfferPrice - $discountAmount;

            // Tax (10% of final price)
            $tax   = round($finalPrice * 0.10, 2);
            $total = $finalPrice + $tax;

            // ========================
            // Return response
            // ========================
            return response()->json([
                'status'          => true,
                'message'         => $coupon->code.' coupon applied.',
                'material_id'     => $material->id,
                'coupon_code'     => $coupon->code,
                'discount_type'   => $coupon->discount_type,
                'discount_value'  => $coupon->discount_value,
                'actual_price'    => $actualPrice,
                'offer_price'     => $offerPrice,
                'number_of_users' => $numberOfUser,
                'total_offer_price'=> $totalOfferPrice,
                'discount_amount' => $discountAmount,
                'final_price'     => $finalPrice,
                'taxPercentage'   => 10.00,
                'tax'             => $tax,
                'total'           => $total
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function applyCouponForAddToCartCoures(Request $request)
    {
        $rules = [
            'jobseeker_id'  => 'required|integer|exists:jobseekers,id',
            'couponCode'   => 'required|exists:coupons,code'
        ];

        $messages = [
            'jobseeker_id.required' => 'Jobseeker ID is required.',
            'jobseeker_id.integer'  => 'Jobseeker ID must be a valid number.',
            'jobseeker_id.exists'   => 'Jobseeker does not exist.',
            
            'couponCode.required'  => 'Coupon code is required.',
            'couponCode.exists'    => 'Coupon code does not exist.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        try {
            $jobseeker_id   = $request->jobseeker_id;            
            $couponCode    = $request->couponCode;

            $cartItems = JobseekerCartItem::with('material')
                ->where('jobseeker_id', $jobseeker_id)
                ->where('status', 'pending')
                ->get();

            $actualPrice = $cartItems->sum(fn($item) => $item->material->training_price ?? 0);
            $offerPrice  = $cartItems->sum(fn($item) => $item->material->training_offer_price ?? 0);

            // Total before discount
            $totalOfferPrice = $offerPrice ;

            // ========================
            // Fetch & validate coupon
            // ========================
            $coupon = Coupon::where('code', $couponCode)
                ->where('is_active', 1)
                ->whereDate('valid_from', '<=', now())
                ->whereDate('valid_to', '>=', now())
                ->first();

            if (!$coupon) {
                return response()->json([
                    'status'  => false,
                    'message' => $couponCode.' is invalid or expired.'
                ], 200);
            }

            // ========================
            // Apply discount
            // ========================
            $discountAmount = 0;

            if ($coupon->discount_type === 'percentage') {
                $discountAmount = ($totalOfferPrice * $coupon->discount_value) / 100;
            } elseif ($coupon->discount_type === 'fixed') {
                $discountAmount = $coupon->discount_value;
            }

            // Prevent discount from exceeding total
            if ($discountAmount > $totalOfferPrice) {
                $discountAmount = $totalOfferPrice;
            }

            $finalPrice = $totalOfferPrice - $discountAmount;

            // Tax (10% of final price)
            $tax   = round($finalPrice * 0.10, 2);
            $total = $finalPrice + $tax;

            // ========================
            // Return response
            // ========================
            return response()->json([
                'status'          => true,
                'message'         => $coupon->code.' coupon applied.',
                'coupon_code'     => $coupon->code,
                'discount_type'   => $coupon->discount_type,
                'discount_value'  => $coupon->discount_value,
                'actual_price'    => $actualPrice,
                'offer_price'     => $offerPrice,
                'total_offer_price'=> $totalOfferPrice,
                'discount_amount' => $discountAmount,
                'final_price'     => $finalPrice,
                'taxPercentage'   => 10.00,
                'tax'             => $tax,
                'total'           => $total
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function applyCouponForSlotForMCA(Request $request)
    {
        $data = $request->all();

        $rules = [
            'user_type'  => 'required',
            'user_id'    => 'required|integer',
            'couponCode' => 'required|exists:coupons,code',
        ];

        $messages = [
            'user_type.required' => 'Type is required.',

            'user_id.required' => ($request->user_type ?? 'User').' ID is required.',
            'user_id.integer'  => ($request->user_type ?? 'User').' ID must be a valid number.',

            'couponCode.required' => 'Coupon code is required.',
            'couponCode.exists'   => 'Coupon code does not exist.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        // Return only the first error
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        try {
            $userId    = $request->user_id;
            $userType  = $request->user_type;
            $couponCode = $request->couponCode;

            // Base slot price & tax %
            $slotPrice = $this->getUserSlotPrice($userId, $userType);
            $slotTax   = $this->getSlotPercentage($userType);

            // Initial total before coupon
            $slotTotalAmount = $slotPrice + ($slotPrice * $slotTax / 100);

            // ========================
            // Fetch & validate coupon
            // ========================
            $coupon = Coupon::where('code', $couponCode)
                ->where('is_active', 1)
                ->whereDate('valid_from', '<=', now())
                ->whereDate('valid_to', '>=', now())
                ->first();

            if (!$coupon) {
                return response()->json([
                    'status'  => false,
                    'message' => $couponCode.' is invalid or expired.'
                ], 200);
            }

            // ========================
            // Apply discount
            // ========================
            $discountAmount = 0;

            if ($coupon->discount_type === 'percentage') {
                $discountAmount = ($slotPrice * $coupon->discount_value) / 100;
            } elseif ($coupon->discount_type === 'fixed') {
                $discountAmount = $coupon->discount_value;
            }

            if ($discountAmount > $slotPrice) {
                $discountAmount = $slotPrice;
            }

            // Final price after discount
            $finalSlotPrice = $slotPrice - $discountAmount;
            $finalTotalAmount = $finalSlotPrice + ($finalSlotPrice * $slotTax / 100);

            return response()->json([
                'status'            => true,
                'message'           => $coupon->code.' coupon applied.',
                'perSlotPrice'      => $slotPrice,
                'discount_amount'   => $discountAmount,
                'finalSlotPrice'    => $finalSlotPrice,
                'slotTaxPercentage' => $slotTax,
                'finalTotalAmount'  => $finalTotalAmount
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
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
        if ($type == 'material') 
        {
            $MentorsDetails = Setting::select('trainingMaterialTax')->where('id', 1)->first();
            return $MentorsDetails->trainingMaterialTax ;
        } 
        elseif ($type == 'batch') 
        {
            $MentorsDetails = Setting::select('trainingMaterialBatchTax')->where('id', 1)->first();
            return $MentorsDetails->trainingMaterialBatchTax ;
        }
       
        return 1 ;
    }

    function generateTrackId($jobseekerId, $trainerId = 0 , $materialId = 0 , $batchId = 0 , $type = 'buyNow')
    {
        // Short code for type
        $typeCode = match($type) {
            'buyNow'         => 'BN',
            'buyForCorporates'=> 'BC',
            'cart'           => 'CT',
            default          => 'OT', // Other
        };

        // Generate unique timestamp (to avoid duplicates)
        $timestamp = Carbon::now()->format('YmdHisv'); // v = milliseconds

        return sprintf(
            "%s-%s-%s-%s-%s-%s",
            $jobseekerId,
            $typeCode,
            $trainerId ?? 0,
            $materialId ?? 0,
            $batchId ?? 0,
            $timestamp
        );
    }
    
}
