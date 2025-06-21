<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user= $request->user();
        if($user && !$user->hasCompletedProfile()){
            return response()->json([
                'message'=>'please complete your profile before proceeding',
                'profile_completed'=>false,
            ], 403);
              return $next($request);
        }

      
    }
}
