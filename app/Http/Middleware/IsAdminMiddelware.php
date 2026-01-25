<?php

namespace App\Http\Middleware;

use App\Enums\LevelUserEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddelware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->level!=LevelUserEnum::ADMIN->value && auth()->user()->level!=LevelUserEnum::STAFF->value){
            abort(403,'ليس مصرح لك بالدخول');
        }
        return $next($request);
    }
}
