<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $notifications = DB::table('notifications')->select('notifications.*','jobseekers.name','jobseekers.email')->join('jobseekers','jobseekers.id','=','notifications.receiver_id'
        )->where(['notifications.receiver_id' => $user->id])->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function view($id)
    {
        $user = Auth::user();
        
        $notifications = DB::table('notifications')->select('notifications.*','jobseekers.name','jobseekers.email')->join('jobseekers','jobseekers.id','=','notifications.receiver_id'
        )->where(['notifications.receiver_id' => $user->id,'notifications.is_read' => 0,'notifications.id' => $id])->first();

        DB::table('notifications')->where('id',$id)->update(['is_read' => 1]);

        return view('admin.notifications.details', compact('notifications'));
    }
}
