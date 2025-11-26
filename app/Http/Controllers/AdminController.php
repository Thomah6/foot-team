<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Page principale du layout Admin
     */
    public function index()
    {
        return Inertia::render('Admin/AdminLayout', 
          
        );
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
