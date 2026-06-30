<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        // Determine user role (matches getUserRole logic in AuthController)
        $userRole = 'customer';
        if (\Illuminate\Support\Str::contains(strtolower($user->email), 'admin')) {
            $userRole = 'admin';
        } elseif (\Illuminate\Support\Str::contains(strtolower($user->email), 'stylist')) {
            $userRole = 'stylist';
        } elseif (\Illuminate\Support\Str::contains(strtolower($user->email), 'staff')) {
            $userRole = 'staff';
        }

        if ($userRole !== $role) {
            return response()->json(['message' => 'Forbidden. Requires role: ' . $role], 403);
        }

        return $next($request);
    }
}
