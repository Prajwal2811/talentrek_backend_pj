<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiToken = $request->header('API-TOKEN');

        if ($apiToken !== 'secret123') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
