<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user=JWTAuth::parsetoken()->authenticate();
    
        if (Auth::check() && Auth::user()->role === 'student') {
            return $next($request); // الطالب مسموح له بالمتابعة
        }

        // في حالة المستخدم ليس طالب
        abort(403, 'Unauthorized - Only students can access this route.');
    }
}
