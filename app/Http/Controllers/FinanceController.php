<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FinanceController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        // Fonction helper pour appliquer les filtres communs
        $applyFilters = function($baseQuery) use ($request) {
            if ($request->member_id)
                $baseQuery->where('user_id', $request->member_id);
            if ($request->date_from)
                $baseQuery->whereDate('created_at', '>=', $request->date_from);
            if ($request->date_to)
                $baseQuery->whereDate('created_at', '<=', $request->date_to);
            if ($request->type)
                $baseQuery->where('type', $request->type);
            if ($request->has('statut_valide') && $request->statut_valide !== '') {
                $stat = $request->statut_valide;
                if (is_string($stat)) {
                    $stat = strtolower($stat) === 'true' || $stat === '1' ? true : ($stat === 'false' || $stat === '0' ? false : null);
                }
                if (!is_null($stat)) {
                    $baseQuery->where('statut_valide', $stat);
                }
            }
            return $baseQuery;
        };

        // Requête filtrée pour la liste
        $query = Finance::with('user');
        $query = $applyFilters($query);
        $finances = $query->orderByDesc('created_at')->get();

        // Somme des cotisations validées (sur la sélection actuelle)
        $solde_cotisations = $applyFilters(Finance::query())
            ->where('statut_valide', true)
            ->where('type', 'cotisation')
            ->sum('montant');

        // Somme des dépenses validées (sur la sélection actuelle)
        $solde_depenses = $applyFilters(Finance::query())
            ->where('statut_valide', true)
            ->where('type', 'dépense')
            ->sum('montant');

        // Solde calculé à partir des résultats filtrés
        $solde_total = $solde_cotisations - $solde_depenses;

        // Totaux en attente (sur la sélection actuelle)
        $total_attente = $applyFilters(Finance::query())
            ->where('statut_valide', false)
            ->where('type', 'cotisation')
            ->sum('montant');

        $nb_attente = $applyFilters(Finance::query())
            ->where('statut_valide', false)
            ->where('type', 'cotisation')
            ->count();

        // --- Totaux globaux (non filtrés) ---
        $solde_cotisations_global = Finance::where('statut_valide', true)
            ->where('type', 'cotisation')
            ->sum('montant');

        $solde_depenses_global = Finance::where('statut_valide', true)
            ->where('type', 'dépense')
            ->sum('montant');

        $solde_total_global = $solde_cotisations_global - $solde_depenses_global;

        $total_attente_global = Finance::where('statut_valide', false)
            ->where('type', 'cotisation')
            ->sum('montant');

        $nb_attente_global = Finance::where('statut_valide', false)
            ->where('type', 'cotisation')
            ->count();
        
        $users = User::all();

        // Return props using camelCase keys expected by the Vue components
        return Inertia::render('Finance/Index', [
            'finances' => $finances->toArray(),
            'soldeCotisations' => (int) $solde_cotisations,
            'soldeDepenses' => (int) $solde_depenses,
            'soldeTotal' => (int) $solde_total,
            'totalAttente' => (int) $total_attente,
            'nbAttente' => $nb_attente,
            // global totals (non-filtered)
            'soldeCotisationsGlobal' => (int) $solde_cotisations_global,
            'soldeDepensesGlobal' => (int) $solde_depenses_global,
            'soldeTotalGlobal' => (int) $solde_total_global,
            'totalAttenteGlobal' => (int) $total_attente_global,
            'nbAttenteGlobal' => $nb_attente_global,
            'users' => $users,
        ]);
    }

    // Formulaire pour un dépôt
    public function createDepot()
    {
        return Inertia::render('Finance/CreateDepot');
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
        $this->authorize('valider', $finance);
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





