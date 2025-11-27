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

        $rules = Regulation::with('children')->orderBy('order')->get();

        return inertia('Regulations/Index', [
            'rules' => $rules,
            'can' => [
                'create' => Gate::allows('create-rule'),

            ],
        ]);

    }

        public function show(Regulation $regulation)
        {
            Gate::authorize('view-rule', $regulation);

            return inertia('Regulations/Show', [
                'rule' => $regulation,
            ]);
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        Gate::authorize('create-rule');

        return inertia('Regulations/Create');
    }

    /**
     * Enregistrer un nouveau règlement.
     */
    public function store(Request $request)
    {
        Gate::authorize('create-rule');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:regulations,id',
            'order' => 'nullable|integer',
        ]);

        Regulation::create($validated);

        return redirect()->route('regulations.index')->with('success', 'Règlement créé avec succès.');
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
            'parent_id' => 'nullable|exists:regulations,id',
            'order' => 'nullable|integer',
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
