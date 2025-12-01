<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        $roles = array_slice(func_get_args(), 2);

        if (!auth()->check()) {
            return redirect('/login');
        }

        $userRole = auth()->user()->role;

        if (empty($roles) || in_array($userRole, $roles)) {
            return $next($request);
        }

        return redirect()->back()->with('error', 'Accès non autorisé');
    }
}
