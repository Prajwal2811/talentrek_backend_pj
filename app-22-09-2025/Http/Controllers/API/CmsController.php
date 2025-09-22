<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

use DB;
use Auth;
use App\Models\Api\CMS;


use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\URL;

class CmsController extends Controller
{
    public function content($slug)
    {
        // Check if slug is empty
        if (empty($slug)) {
            return response()->json([
                'status'  => false,
                'message' => 'Slug is required',
                'data'    => null
            ], 400);
        }

        // Fetch CMS content with extra conditions
        $terms = CMS::where('slug', $slug)
                   // ->where('status', 1) // only active records
                    ->first();

        if (!$terms) {
            return response()->json([
                'status'  => false,
                'message' => 'Content not found',
                'data'    => null
            ], 404);
        }

        // Success response
        return response()->json([
            'status'  => true,
            'message' => 'Content fetched successfully',
            'data'    => $terms
        ], 200);
    }    
}
