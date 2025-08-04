<?php

namespace App\Http\Controllers\API\Jobseeker;
use App\Models\Api\CMS;
use App\Models\Api\Mentors;
use App\Models\Api\Testimonial;
use App\Models\Api\TrainingMaterial;
use App\Models\Api\TrainingPrograms;
use App\Models\Api\Trainers;
use App\Models\JobseekerCartItem;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use DB;
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
                    $query->select('id', 'training_title', 'training_descriptions','thumbnail_file_path as image','training_price','training_offer_price'); // Customize fields as needed
                }
            ])
            ->where('jobseeker_id', $jobseekerId)
            ->where('status', 'pending')
            ->get()->map(function ($item) {
                $material = $item->material;
                $item->final_price = (!empty($material->training_offer_price))
                    ? $material->training_offer_price
                    : $material->training_price;
                return $item;
            });

            // 💰 Get sum of final_price
            $totalPrice = $items->sum('final_price');
            if ($items->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No items found in cart.'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Item list retrieved successfully.','totalPrice' => $totalPrice, 'data' => $items]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
