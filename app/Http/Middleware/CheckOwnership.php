<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $model
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next, string $model): Response
    {
        $user = $request->user();

        // Allow admins to bypass ownership checks
        if ($user && $user->hasRole('admin', 'sanctum')) {
            return $next($request);
        }

        // Check ownership for non-admin users
        $resource = $request->route($model);
        if (!$user || !$resource || $resource->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'errors' => 'Unauthorized: You do not own this resource'
            ], 403);
        }

        return $next($request);
    }
}