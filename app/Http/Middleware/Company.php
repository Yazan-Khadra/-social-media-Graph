<?php

namespace App\Http\Middleware;

use App\JsonResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Company
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
        if ($user->role !== 'company') {
            return $this->JsonResponse('Access denied. Only companies can perform this action.', 403);
        }

        return $next($request);
    }
}
