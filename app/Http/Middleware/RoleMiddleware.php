<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage in route: ->middleware('role:admin')
     * Usage multiple roles: ->middleware('role:admin|manager')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = Auth::user();

        // Si pas authentifié -> rediriger vers login
        if (! $user) {
            return redirect()->route('login');
        }

        // Supporte "admin" ou "admin|editor" (pipe séparateur)
        $allowed = explode('|', $roles);

        // Si l'utilisateur a une méthode hasRole() sur le modèle
        if (method_exists($user, 'hasRole')) {
            foreach ($allowed as $role) {
                if ($user->hasRole(trim($role))) {
                    return $next($request);
                }
            }
        } else {
            // Cas simple : la colonne 'role' contient une chaîne (ex: 'admin')
            $userRole = $user->role ?? null;
            if (in_array($userRole, $allowed, true)) {
                return $next($request);
            }
        }

        // Sinon : 403 Forbidden (ou rediriger)
        abort(403, 'Accès refusé — rôle requis : ' . $roles);
    }
}
