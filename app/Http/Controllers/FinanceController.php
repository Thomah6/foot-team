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

    // Soumettre un dépôt
    public function store(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:5|max:1000',
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

    


}
