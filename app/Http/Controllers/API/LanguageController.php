<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Language;

class LanguageController extends Controller
{
    public function index(Request $request)
    {
        
        try {
            $response = Language::query()
                ->select('code','english','arabic')
                ->get();            

            return response()->json([
                    'status' => true,
                    'message' => 'Fetch Translation',
                    'data' => $response
                ]);   

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch translation.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }


}