<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckSimplePanelAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Check if user exists and has a valid role
        if (!$user || !$user->role || !is_string($user->role)) {
            abort(403, 'Access denied. Invalid user or role.');
        }
        
        // Check if user has panel_access.simple permission
        try {
            if (!userHasPermission($user, 'panel_access.simple')) {
                abort(403, 'Access denied. You do not have permission to access the simple panel.');
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Permission check failed', [
                'user_id' => $user->id ?? 'unknown',
                'user_role' => $user->role ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            abort(403, 'Access denied. Permission check failed.');
        }

        return $next($request);
    }
}
