<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
public function share(Request $request): array
{
    $user = $request->user();

    return array_merge(parent::share($request), [
        'auth' => [
            'user' => $user ? [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ] : null,

            // Empêche totalement l’erreur SQL
            'permissions' => (
                $user &&
                \Illuminate\Support\Facades\Schema::hasTable('permissions') &&
                method_exists($user, 'getPermissionNames')
            )
                ? $user->getPermissionNames()
                : [],
        ],
    ]);
}
}
