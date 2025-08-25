<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckUserLock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */



    public function handle(Request $request, Closure $next)
    {
        $user = User::where('matricule', $request['matricule'])->first();

        if ($user && $user->loginAttempt) {
            $attempts = $user->loginAttempt->attempts;
            $lockedUntil = $user->loginAttempt->locked_until;

            if ($lockedUntil && Carbon::now()->lessThan($lockedUntil)) {
                return response()->json(['message' => 'Account is locked.'], 423);
            }

            if ($attempts >= 3) {
                $user->loginAttempt->update([
                    'locked_until' => Carbon::now()->addMinutes(60),
                    'attempts' => 0,
                ]);

                $user->update(['is_locked' => true]); // on marque le compte comme verrouillé

                return response()->json(['message' => 'Account is locked.'], 423);
            }

            // 🔓 Si plus de verrouillage
            if ($user->is_locked && (!$lockedUntil || Carbon::now()->greaterThan($lockedUntil))) {
                $user->update(['is_locked' => false]);
            }
        }

        return $next($request);
    }
}