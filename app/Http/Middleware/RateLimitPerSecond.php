<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitPerSecond
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user=auth()->user();
        $key = 'rate_limit:' . $user->id;
        $limit = 70; // عدد الطلبات المسموح بها في الثانية

        $current = Cache::get($key, 0);
        abort_if($current >= $limit, 429, 'تم تجاوز الحد المسموح به من الطلبات (70 في الثانية)');


        // زيادة العداد
        Cache::put($key, $current + 1, now()->addSeconds(1));

        return $next($request);
    }
}
