<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Finance::with('user');

        // Filtres
        if ($request->member_id)
            $query->where('user_id', $request->member_id);
        if ($request->date_from)
            $query->where('created_at', '>=', $request->date_from);
        if ($request->date_to)
            $query->where('created_at', '<=', $request->date_to);

        $finances = $query->orderByDesc('created_at')->paginate(20);

        // Données calculées
        $solde_cotisations = Finance::where('statut_valide', true)
            ->where('type', 'cotisation')
            ->sum('montant');

        $solde_depenses = Finance::where('statut_valide', true)
            ->where('type', 'dépense')
            ->sum('montant');

        $solde_total = $solde_cotisations - $solde_depenses;

        $total_attente = Finance::where('statut_valide', false)
            ->where('type', 'cotisation')->sum('montant');

        return Inertia::render('Finance/Index', compact('finances', 'solde_cotisations', 'solde_depenses', 'solde_total', 'total_attente'));
    }

    // Formulaire pour un dépôt
    public function createDepot()
    {
        return Inertia::render('Finance/Create');
    }

    // Soumettre un dépôt
    public function storeDepot(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:100|max:10000',
            'description' => 'nullable|string'
        ]);
        Finance::create([
            'user_id' => auth()->id(),
            'montant' => $request->montant,
            'type' => 'cotisation',
            'statut_valide' => false,
            'description' => $request->description,
        ]);
        return back()->with('success', 'Dépôt effectué avec succès ! Attente validation admin.');
    }

    // Validation par admin
    public function valider($id)
    {
        $finance = Finance::findOrFail($id);
        // $this->authorize('valider', $finance);
        $finance->update(['statut_valide' => true]);
        return back()->with('success', 'Dépôt validé.');
    }

    public function createDepense()
    {
        return Inertia::render('Finance/CreateDepense');
    }

    public function storeDepense(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:100|max:100000',
            'description' => 'required|string'
        ]);
        Finance::create([
            'user_id' => auth()->id(),
            'montant' => $request->montant,
            'type' => 'dépense',
            'statut_valide' => true,
            'description' => $request->description,
        ]);
        return back()->with('success', 'Dépense ajoutée.');
    }
}





