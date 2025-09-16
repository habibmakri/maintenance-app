<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEntreprise
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('entreprise')->check()) {
            return redirect()->route('inscription.login_entreprise'); 
        }

        $entreprise = Auth::guard('entreprise')->user();

        view()->share('entreprise', $entreprise);

        return $next($request);
    }
}