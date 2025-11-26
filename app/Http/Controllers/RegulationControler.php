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
                'update' => Gate::allows('update-rule'),
                'delete' => Gate::allows('delete-rule'),
            ],
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
    public function edit(Regulation $rule)
    {
        Gate::authorize('update-rule', $rule);

        return inertia('Rules/Edit', [
            'rule' => $rule,
        ]);
    }

    /**
     * Mettre à jour un règlement.
     */
    public function update(Request $request, Regulation $rule)
    {
        Gate::authorize('update-rule', $rule);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:regulations,id',
            'order' => 'nullable|integer',
        ]);

        $rule->update($validated);

        return redirect()->route('regulations.index')->with('success', 'Règlement mis à jour.');
    }

    /**
     * Supprimer un règlement.
     */
    public function destroy(Regulation $rule)
    {
        Gate::authorize('delete-rule', $rule);

        $rule->delete();

        return redirect()->route('regulations.index')->with('success', 'Règlement supprimé.');
    }
}
