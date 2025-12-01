<?php

namespace App\Http\Controllers;

use App\Models\Regulation;
use App\Models\RegulationContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RegulationControler extends Controller
{
    /**
     * Afficher la liste des règlements avec contenus.
     */
    public function index()
    {
        Gate::authorize('viewAny-rule');

        $titles = Regulation::with('contents')->orderBy('created_at')->get();

        return inertia('Regulations/Index', [
            'titles' => $titles,
            'can' => [
                'create' => Gate::allows('create-rule'),
            ],
        ]);
    }

    public function fusion(Request $request)
    {
        Gate::authorize('create-rule');

        // 1️⃣ Validation des données envoyées depuis le formulaire
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'contents' => 'required|array', // Tableau de sous-points
            'contents.*.sub_number' => 'required|string|max:10',
            'contents.*.content' => 'required|string',
            'contents.*.order' => 'nullable|integer',
        ]);

        // 2️⃣ Création du titre
        $regulation = Regulation::create([
            'title' => $validated['title'],
        ]);

        // 3️⃣ Création des contenus associés
        foreach ($validated['contents'] as $content) {
            RegulationContent::create([
                'regulation_id' => $regulation->id,
                'sub_number' => $content['sub_number'],
                'content' => $content['content'],
                'order' => $content['order'] ?? 0,
            ]);
        }

        // 4️⃣ Redirection avec message de succès
        return redirect()->route('regulations.index')->with('success', 'Règlement et contenus créés avec succès.');
    }


    /**
     * Formulaire de création fusionné (titre + contenu).
     */
    public function create()
    {
        Gate::authorize('create-rule');

        $titles = Regulation::all();

        return inertia('Regulations/Create', [
            'titles' => $titles,
        ]);
    }

    /**
     * Stocke un titre et son contenu.
     */
    public function store(Request $request)
    {
        Gate::authorize('create-rule');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sub_number' => 'required|string|max:10',
            'content' => 'required|string',
            'order' => 'nullable|integer'
        ]);

        // Créer le titre
        $regulation = Regulation::create([
            'title' => $validated['title']
        ]);

        // Créer le contenu lié
        RegulationContent::create([
            'regulation_id' => $regulation->id,
            'sub_number' => $validated['sub_number'],
            'content' => $validated['content'],
            'order' => $validated['order'] ?? null
        ]);

        return redirect()->route('regulations.index')->with('success', 'Règlement ajouté avec succès.');
    }

    /**
     * Formulaire d’édition d’un titre et contenus liés.
     */
    public function edit(Regulation $regulation)
    {
        Gate::authorize('update-rule', $regulation);

        $regulation->load('contents');

        return inertia('Regulations/Edit', [
            'rule' => $regulation
        ]);
    }

    /**
     * Mettre à jour le titre.
     */
    public function updateTitle(Request $request, Regulation $regulation)
    {
        Gate::authorize('update-rule', $regulation);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $regulation->update($validated);

        return redirect()->route('regulations.index')->with('success', 'Titre mis à jour.');
    }

    public function editContent(RegulationContent $content)
    {
        Gate::authorize('update-rule', $content->regulation);

        return inertia('Regulations/Edit', [
            'content' => $content,
            'title' => $content->regulation->title,
        ]);
    }


    /**
     * Mettre à jour un contenu spécifique.
     */
    public function updateContent(Request $request, RegulationContent $content)
    {
        Gate::authorize('update-rule', $content->regulation);

        $validated = $request->validate([
            'sub_number' => 'required|string|max:10',
            'content' => 'required|string',
            'order' => 'nullable|integer'
        ]);

        $content->update($validated);

        return redirect()->route('regulations.index')->with('success', 'Contenu mis à jour.');
    }

    /**
     * Supprimer un titre et tous ses contenus.
     */
    public function destroy(Regulation $regulation)
    {
        Gate::authorize('delete-rule', $regulation);

        $regulation->contents()->delete();
        $regulation->delete();

        return redirect()->route('regulations.index')->with('success', 'Règlement supprimé.');
    }

    /**
     * Supprimer un contenu spécifique.
     */
    public function destroyContent(RegulationContent $content)
    {
        Gate::authorize('delete-rule', $content->regulation);

        $content->delete();

        return redirect()->route('regulations.index')->with('success', 'Contenu supprimé.');
    }
}
