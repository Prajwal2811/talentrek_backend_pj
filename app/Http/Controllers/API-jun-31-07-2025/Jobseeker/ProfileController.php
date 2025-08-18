<?php

namespace App\Http\Controllers\API;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        return view('home');
    }
}
