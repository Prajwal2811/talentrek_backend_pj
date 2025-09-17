<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Language;

if (!function_exists('hasModuleAccess')) {
    function hasModuleAccess($module)
    {
        $user = Auth::user();
        return $user && ($user->role === 'superadmin' || in_array($module, $user->permissions ?? []));
    }
}

if (!function_exists('notificationSent')) {
    function notificationSent()
    {
        $user = Auth::user();
        $notifications = DB::table('notifications')->select('notifications.*','jobseekers.name')->join('jobseekers','jobseekers.id','=','notifications.receiver_id'
        )->where(['notifications.is_read' => 0])->orderBy('id','DESC')->get();
        return $notifications;
    }
}


// if (!function_exists('notificationUsersSent')) {
//     function notificationUsersSent($user_type)
//     {
//         $user = Auth::user();
//         $notifications = DB::table('notifications')->select('notifications.*','jobseekers.name')->join('jobseekers','jobseekers.id','=','notifications.receiver_id'
//         )->where(['notifications.is_read_users' => 0,'user_type' => 'jobseeker'])->get();
//         return $notifications;
//     }
// }

if (!function_exists('notificationUsersSent')) {
    function notificationUsersSent($user_type)
    {
        $user = Auth::user();

        $tables = [
            'jobseeker' => ['table' => 'jobseekers', 'name' => 'jobseekers.name'],
            'trainer'   => ['table' => 'trainers', 'name' => 'trainers.name'],
            'mentor'    => ['table' => 'mentors', 'name' => 'mentors.name'],
            'coach'     => ['table' => 'coach', 'name' => 'coach.name'],
            'assessor'  => ['table' => 'assessors', 'name' => 'assessors.name'],
            'recruiter' => ['table' => 'recruiters', 'name' => 'recruiters.name'],
        ];

        if (!isset($tables[$user_type])) {
            return collect();
        }

        $table  = $tables[$user_type]['table'];
        $name   = $tables[$user_type]['name'];

        $notifications = DB::table('notifications')
            ->select('notifications.*', $name . ' as sender_name')
            ->join($table, $table . '.id', '=', 'notifications.sender_id')
            ->where([
                'notifications.is_read_users' => 0,
                'notifications.user_type' => $user_type
            ])
            ->orderBy('notifications.id', 'DESC')
            ->get();

        return $notifications;
    }
}

if (!function_exists('langLabel')) {
    function langLabel($code)
    {
        $lang = session('lang', 'english'); // default language

        // Cache translations for performance (optional)
        $translations = Cache::remember("language_labels_{$lang}", 60, function () use ($lang) {
            return Language::pluck($lang, 'code')->toArray();
        });

        return $translations[$code] ?? ucfirst($code);
    }
}

