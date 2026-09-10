<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModeSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only redirect web requests if user is authenticated and hasn't selected a mode yet (mode is null)
        if ($user && is_null($user->mode)) {
            if (!$request->is('onboarding*') && !$request->is('logout') && !$request->is('api/*')) {
                return redirect('/onboarding');
            }
        }

        return $next($request);
    }
}
