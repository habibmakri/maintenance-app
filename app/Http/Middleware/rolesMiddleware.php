<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class rolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $userPermissions = !empty(Auth::user()->autorisations) ? explode(',', Auth::user()->autorisations) : [];
        if (in_array($permission, $userPermissions)) {
            return $next($request);
        }else{
            abort(404);
        }
    }
}
