<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $module
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $module)
    {
        $admin = Auth::guard('admin')->user(); // or just Auth::user() if single guard

        if (!$admin || $admin->role !== 'superadmin') {
            $permissions = $admin->permissions ?? [];

            if (!in_array($module, $permissions)) {
                abort(403, 'Unauthorized access to "' . $module . '" module.');
            }
        }

        return $next($request);
    }
}
