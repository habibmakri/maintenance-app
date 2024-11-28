<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(404); 
        }
        $user = Auth::user();
        $name = $user ? $user->firstname . ' ' . $user->lastname : '???';
        $permissions = explode(',', $user->autorisations); 

        view()->share('name', $name);
        view()->share('permissions', $permissions);

        return $next($request);
    }
}
