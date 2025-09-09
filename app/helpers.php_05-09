<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('hasModuleAccess')) {
    function hasModuleAccess($module)
    {
        $user = Auth::user();
        return $user && ($user->role === 'superadmin' || in_array($module, $user->permissions ?? []));
    }
}
