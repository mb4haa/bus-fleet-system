<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$roles):JsonResponse
    {
        $user = $request->user();
        
        if (! $user || ! in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Unauthorized',
                'status' => Response::HTTP_FORBIDDEN
            ]);
        }
        
        return $next($request);
    }
    
}

