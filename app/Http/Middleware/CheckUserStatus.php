<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Vérifier si l'utilisateur est actif
        if (property_exists($user, 'is_active') && !$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('status', 'Votre compte a été désactivé. Veuillez contacter un administrateur.');
        }

        return $next($request);
    }
}