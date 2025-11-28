<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Nette\MemberAccessException;

class AdminController extends Controller
{
    /**
     * Page principale du layout Admin
     */
public function index()
{
    // Nombre total d'utilisateurs
    $totalUsers = \App\Models\User::count();

    // Balance totale de la caisse (somme de la colonne amount dans finances)
    $balance = \App\Models\Finance::sum('montant');

    // On regroupe les stats dans un tableau
    $stats = [
        'totalUsers' => $totalUsers,
        'balance'    => $balance,
    ];

    return Inertia::render('Admin/AdminLayout', [
        'stats' => $stats
    ]);
}




    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'users' => 125,
                'posts' => 48,
                'messages' => 12,
            ],
        ]);
    }

    /**
     * Page Paramètres Admin
     */
    public function settings()
    {
        return Inertia::render('Admin/Settings', [
            'settings' => [
                'maintenanceMode' => false,
                'emailNotifications' => true,
            ],
        ]);
    }

    /**
     * Exemple : Gestion utilisateurs Admin
     */
    public function users()
    {
        return Inertia::render('Admin/Users', [
            'users' => \App\Models\User::latest()->paginate(10)
        ]);
    }

    /**
     * Exemple : Page Profil Admin (optionnel)
     */
    public function profile()
    {
        return Inertia::render('Admin/Profile', [
            'admin' => auth()->user(),
        ]);
    }
}
