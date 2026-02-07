<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->city_id==null || auth()->user()->area_id==null || auth()->user()->address==null
        || auth()->user()->full_phone==null){
            return redirect()->route('profile.index')->with('error','يرجى إكمال الملف الشخصي قبل ان تتمكن من إضافة المنتجات');
        }
        return $next($request);
    }
}
