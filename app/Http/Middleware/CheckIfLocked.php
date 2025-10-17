<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfLocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    { 
        $user = $request->user();

        if ($user && $user->is_locked) {
            return response()->json([
                'message' => 'Compte verrouillé. Contactez un administrateur.'
            ], 403);
        }

        return $next($request);
    
    }
}
