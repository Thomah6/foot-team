<?php

namespace App\Http\Controllers;

use App\Models\Reflection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class ReflectionController extends Controller
{
    /**
     * Affiche la page publique avec le formulaire et la liste des réflexions.
     */
    public function index()
    {
        // 1. Listing réflexions (filtrées par activation et durée)
        $reflections = Reflection::query()
            ->with('user') // Relation avec User
            // ->where('created_at', '>', now()->subDays(7)) // Durée limitée (ex: 7 jours)
            // ->latest()
            ->get();

        return Inertia::render('Reflections/Index', [
            'reflections' => $reflections,
        ]);
    }

    /**
     * Gère la soumission d'une nouvelle réflexion.
     */
    public function store(Request $request)
    {
        // Validation du formulaire soumission réflexion
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        // Création et Relation avec User
        Auth::user()->reflections()->create([
            'content' => $request->input('content'),
            'is_active' => true, // Par défaut, active (ajustez si vous voulez une modération)
        ]);

        return redirect()->back()
            ->with('success', 'Votre réflexion a été soumise avec succès !');
    }

    // --- Interface Admin Gestion ---

    /**
     * Affiche l'interface d'administration pour la gestion des réflexions.
     * Accessible uniquement par les administrateurs (vous devrez implémenter la logique d'autorisation).
     */
    public function adminIndex()
    {
        $this->authorize('viewAny', Reflection::class); // Exemple de vérification d'autorisation

        $allReflections = Reflection::query()
            ->with('user:id,name')
            ->latest()
            ->get();

        return Inertia::render('Admin/Reflections', [
            'reflections' => $allReflections,
        ]);
    }

    /**
     * Système activation/désactivation admin.
     * Change l'état d'activation d'une réflexion.
     */
    public function toggleActivation(Reflection $reflection)
    {
        $this->authorize('update', $reflection); // Vérification d'autorisation

        $reflection->update([
            'is_active' => !$reflection->is_active,
        ]);

        return redirect()->back()
            ->with('success', 'Le statut de la réflexion a été mis à jour.');
    }

    /**
     * Supprime une réflexion (Admin).
     */
    public function destroy(Reflection $reflection)
    {
        $this->authorize('delete', $reflection); // Vérification d'autorisation
        $reflection->delete();

        return redirect()->back()
            ->with('success', 'La réflexion a été supprimée.');
    }
}
