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
            // abort(404);
           return redirect()->route('login'); 
        }else{
            $user = Auth::user();
            $name = $user ? $user->firstname . ' ' . $user->lastname : '???';
            $permissions = explode(',', $user->autorisations); 
            $service = $user->service;
            $poste = $user->poste;
            view()->share('name', $name);
            view()->share('permissions', $permissions);
            view()->share('service', $service);
            view()->share('poste', $poste);
    
            return $next($request);
        }
    }
}
