<?php

namespace App\Http\Middleware;

use Closure;
use App\JsonResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class StudentMiddleware
{
       use JsonResponseTrait;
         /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
       public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return $this->JsonResponse('Unauthorized access', 401);
        }

        // Check if user has company role
        if ($user->role !== 'student') {
            return $this->JsonResponse('Access denied. Only student can perform this action.', 403);
        }

        return $next($request);
    }

}
