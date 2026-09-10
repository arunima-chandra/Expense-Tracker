<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isCompanyAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Only company admins can perform this action.'
                ], 403);
            }

            abort(403, 'Unauthorized. Only company admins can access this area.');
        }

        return $next($request);
    }
}
