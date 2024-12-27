<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForActiveToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->tokens()->count() > 0) {
            return response(['status' => 403, 'message' => 'failed', 'error' => 'User already has an active token']);
        }
        return $next($request);
    }
}
