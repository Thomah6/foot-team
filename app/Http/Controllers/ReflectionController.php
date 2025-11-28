<?php

namespace App\Http\Controllers;

use App\Models\Reflection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommentController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReflectionController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Affiche la page publique avec le formulaire et la liste des réflexions.
     */
    public function index(Request $request)
    {
        // 1. Listing réflexions (filtrées par activation et durée)
        $reflections = Reflection::query()
            ->with('user')
            ->with('votes') // Relation avec vote
            // ->where('created_at', '>', now()->subDays(7)) // Durée limitée (ex: 7 jours)
            // ->latest()
            ->get();
        // dd($reflections->toArray());
        $isAdmin = Auth::user()->role === 'admin';
        if($isAdmin)
        {
            return Inertia::render('Reflections/Index', [
            'reflections' => $reflections,
            'success' => $request->session()->get('success'),
            ]);

        }else{
            return Inertia::render('Reflections/AdminIndex', [
            'reflections' => $reflections,
            'success' => $request->session()->get('success'),
            ]);

        }
        
    }
    public function show(Reflection $reflection){


        

        $comments=CommentController::ravel($reflection);

        $reflection->load('user', 'comments');
        // dd($comments);
        // je charge manuellement les relations 'user' et 'comments'
        // avant de passer l'objet à la vue.

        $voteController = new VoteController;
        $returnVote = $voteController->index($reflection->id);
        $isVoteEnded = now()->greaterThanOrEqualTo($reflection->date_fin_vote);
        $isAdmin = Auth::user()->role === 'admin';
        if($isAdmin)
        {
            return Inertia::render('Reflections/Show', [
                'reflection' => $reflection,
                'comments'=>$comments,
                'isVoteEnded' => $isVoteEnded,
                'isAdmin' => $isAdmin,
                ...$returnVote,
            ]);
        }else{
            return Inertia::render('Reflections/Show', [
                'reflection' => $reflection,
                'comments'=>$comments,
                'isVoteEnded' => $isVoteEnded,
                'isAdmin' => $isAdmin,
                ...$returnVote,
            ]);
        }
        
    }

    /**
     * Gère la soumission d'une nouvelle réflexion.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string|max:2000',
            'date_fin_vote' => 'nullable|date|after:now',
        ]);

        $isAdmin = Auth::user()->role === 'admin';
        
        $reflection = Auth::user()->reflections()->create([
            'titre' => $validated['titre'],
            'contenu' => $validated['contenu'],
            'statut' => $isAdmin ? 'ouvert' : 'en_attente',
            'is_active' => $isAdmin, // Active directement si admin
            'date_fin_vote' => $validated['date_fin_vote'] ?? now()->addWeek(),
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
        $this->authorize('update', $reflection);
        
        $reflection->update([
            'is_active' => !$reflection->is_active,
            'statut' => $reflection->is_active ? 'ferme' : 'ouvert'
        ]);

        return redirect()->back()
            ->with('success', 'Le statut de la réflexion a été mis à jour.');
    }
    
    /**
     * Valide une réflexion (Admin)
     */
    public function validateReflection(Reflection $reflection)
    {
        $this->authorize('update', $reflection);
        
        $reflection->update([
            'statut' => 'valide',
            'is_active' => true
        ]);

        return redirect()->back()
            ->with('success', 'La réflexion a été validée avec succès.');
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
