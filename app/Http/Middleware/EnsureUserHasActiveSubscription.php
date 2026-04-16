<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hasSubscription = Cache::remember("user_{$request->user()->id}_has_subscription", 60, function () use ($request) {
            return $request->user()->hasActiveSubscription();
        });

        if (! $hasSubscription) {
            return response()->json([
                'message' => 'You need an active subscription to access this resource.',
            ], 403);
        }

        return $next($request);
    }
}
