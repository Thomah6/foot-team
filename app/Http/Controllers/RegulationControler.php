<?php

namespace App\Http\Controllers;

use App\Models\Regulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RegulationControler extends Controller
{
    /**
     * Afficher la liste des règlements.
     */
    public function index()
    {
        Gate::authorize('viewAny-rule');

        // On récupère tous les règlements (titres + contenus)
        $rules = Regulation::orderBy('created_at')->get();

        return inertia('Regulations/Index', [
            'rules' => $rules,
            'can' => [
                'create' => Gate::allows('create-rule'),
            ],
        ]);
    }


        /**
     * Afficher un règlement (titre ou contenu).
     */
        public function show(Regulation $regulation)
        {
            Gate::authorize('view-rule', $regulation);

            return inertia('Regulations/Show', [
                'rule' => $regulation,
            ]);
    }

    // Création d’un titre
        // public function storeTitle(Request $request)
        // {
        //     $validated = $request->validate([
        //         'title' => 'required|string|max:255',
        //     ]);

        //     Regulation::create([
        //         'title' => $validated['title'],
        //         'content' => null, // pas de contenu
        //     ]);

        //     return redirect()->route('regulations.index')->with('success', 'Titre créé avec succès.');
        // }

        // public function createTitle()
        // {
        //     Gate::authorize('create-rule');

        //     return inertia('Regulations/CreateTitle');
        // }

        // Création d’un contenu lié à un titre
        public function storeContent(Request $request)
        {
            $validated = $request->validate([
                'title' => 'required|string|exists:regulations,title',
                'content' => 'nullable|string',
            ]);

            Regulation::create($validated);

            return redirect()->route('regulations.index')->with('success', 'Contenu ajouté avec succès.');
        }



    /**
     * Formulaire de création de contenu.
     */
    public function create()
    {
        Gate::authorize('create-rule');

        // On récupère uniquement les titres (content = null)
        $titles = Regulation::whereNull('content')->get();

        return inertia('Regulations/Create', [
            'titles' => $titles,
        ]);
    }




    

        /**
     * Formulaire d’édition.
     */
    public function edit(Regulation $regulation)
    {
        Gate::authorize('update-rule', $regulation);

        return inertia('Regulations/Edit', [
            'rule' => $regulation,
        ]);
    }

    /**
     * Mettre à jour un règlement.
     */
    public function update(Request $request, Regulation $regulation)
    {
        Gate::authorize('update-rule', $regulation);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $regulation->update($validated);

        return redirect()->route('regulations.index')->with('success', 'Règlement mis à jour.');
    }

    /**
     * Supprimer un règlement.
     */
    public function destroy(Regulation $regulation)
    {
        Gate::authorize('delete-rule', $regulation);

        $regulation->delete();

        return redirect()->route('regulations.index')->with('success', 'Règlement supprimé.');
    }
}
