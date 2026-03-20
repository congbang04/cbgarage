<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTechnicianRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user is a technician
        if ($user->role !== 'technician' || !$user->technician) {
            return response()->json([
                'error' => 'Bạn không phải kỹ thuật viên',
            ], 403);
        }

        return $next($request);
    }
}
