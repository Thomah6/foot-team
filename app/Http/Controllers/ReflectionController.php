<?php

namespace App\Http\Controllers;

use App\Models\Reflection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommentController;

class ReflectionController extends Controller
{
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
            return Inertia::render('Reflections/AdminIndex', [
            'reflections' => $reflections,
            'success' => $request->session()->get('success'),
            ]);

        }else{
            return Inertia::render('Reflections/Index', [
            'reflections' => $reflections,
            'success' => $request->session()->get('success'),
            ]);

        }

    }



    public function update(Request $request, $id)
{
    // dd($request->all());
    // 1️⃣ Récupérer la réflexion
    $reflection = Reflection::findOrFail($id);

    // 2️⃣ Vérifier que l'utilisateur connecté est bien le propriétaire
    if ($reflection->user_id !== auth()->id()) {
        abort(403, "Vous n'êtes pas autorisé à modifier cette réflexion.");
    }

    // 3️⃣ Validation des données envoyées depuis le formulaire
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'contenu' => 'required|string',
    ]);

    // 4️⃣ Mise à jour dans la base de données
    $reflection->update($validated);

    // 5️⃣ Retour Inertia
    return back()->with('success', 'Réflexion mise à jour avec succès.');
}




    public function show(Reflection $reflection){




        $comments=CommentController::ravel($reflection);

        $reflection->load('user', 'comments');
        // dd($comments);
        // je charge manuellement les relations 'user' et 'comments'
        // avant de passer l'objet à la vue.

        $voteController = new VoteController;
        $returnVote = $voteController->index($reflection->id);
        // dd($returnVote);
        // Guard: some reflections may not have a "date_fin_vote" set.
        // Avoid passing null to Carbon comparison which would throw a TypeError.
        $isVoteEnded = $reflection->date_fin_vote
            ? now()->greaterThanOrEqualTo($reflection->date_fin_vote)
            : false;
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

        // dd($request->all());
        // Validation du formulaire soumission réflexion
        $request->validate([
            'titre'=>'required',
            'contenu' => 'required|string|max:500',
        ]);

        if(Auth::user()->role==="admin"){// je mets automatiquement le statut sur ouvert directement puisqu'li n'a pas beasoi d'être valider par quelqu'un d'autre
            // Création et Relation avec User en mettant les statut sur ouvert
            $request->statut="ouvert";
            $request->user_id=Auth::user();
            Auth::user()->reflections()->create([
                'titre' => $request->input('titre'),
                'contenu' => $request->input('contenu'),
                'statut' => "ouvert",
            ]);
        }else{
            // Création et Relation avec User en mettant les statut sur ouvert
            Auth::user()->reflections()->create([
                'content' => $request->input('content'),
                'statut' => "ferme",
            ]);

        }

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
     * Valide une réflexion (Admin)
     */
    public function validateReflection(Reflection $reflection)
    {
        // $this->authorize('update', $reflection);

        $reflection->update([
            'statut' => 'valide',
        ]);

        return redirect()->back()
            ->with('success', 'La réflexion a été validée avec succès.');
    }
    public function openVote($reflection){
         $reflection->update([
            'statut' => 'ouvert',
            'date_fin_vote'=>now()->addDays(14),
         ]);

        return redirect()->back()
            ->with('success', 'La réflexion a été validée avec succès.');
    }

    /**
     * Supprime une réflexion (Admin).
     */
    public function destroy(Reflection $reflection)
    {
        // dd($reflection->toArray());
        // $this->authorize('delete', $reflection); // Vérification d'autorisation
        $reflection->delete();

        return redirect(route('reflections.index'))
            ->with('success', 'La réflexion a été supprimée.');
    }
}
