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
    $query = Finance::with('user');

    if ($request->member_id) {
        $query->where('user_id', $request->member_id);
    }
    if ($request->date_from) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }
    if ($request->date_to) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }
    if ($request->type) {
        $query->where('type', $request->type);
    }
    if ($request->statut_valide !== null) {
        $query->where('statut_valide', $request->statut_valide);
    }

    // IMPORTANT: pagination
    $finances = $query
        ->orderByDesc('created_at')
        ->paginate(10) // 10 lignes par page (change si tu veux)
        ->through(function ($f) {
            return [
                'id' => $f->id,
                'date' => $f->created_at->format('d/m/Y H:i'),
                'user_name' => $f->user?->name,
                'description' => $f->description,
                'montant' => $f->montant,
                'type' => $f->type,
                'statut_valide' => $f->statut_valide,
            ];
        });

    // Calcul des soldes
    $solde_cotisations = Finance::where('type', 'cotisation')
        ->where('statut_valide', true)
        ->sum('montant');

    $solde_depenses = Finance::where('type', 'dépense')
        ->where('statut_valide', true)
        ->sum('montant');

    // Inclure les ajustements dans le solde total
    $solde_ajustements = Finance::where('type', 'ajustement')
        ->where('statut_valide', true)
        ->sum('montant');

    $solde_total = $solde_cotisations - $solde_depenses + $solde_ajustements;

    // Calcul des montants en attente
    $total_attente = Finance::where('statut_valide', false)
        ->where('type', 'cotisation')
        ->sum('montant');

    $nb_attente = Finance::where('statut_valide', false)
        ->where('type', 'cotisation')
        ->count();

    // Récupérer la liste des utilisateurs pour le filtre
    $users = User::orderBy('name')->get(['id', 'name']);

    // Ajouter les filtres à la pagination pour qu'ils persistent
    $finances->appends([
        'member_id' => $request->member_id,
        'date_from' => $request->date_from,
        'date_to' => $request->date_to,
        'type' => $request->type,
        'statut_valide' => $request->statut_valide,
    ]);

    return Inertia::render('Finance/Index', [
        'finances' => $finances,   // NE PAS convertir en ->toArray() ici
        'soldeCotisations' => (int)$solde_cotisations,
        'soldeDepenses' => (int)$solde_depenses,
        'soldeTotal' => (int)$solde_total,
        'totalAttente' => (int)$total_attente,
        'nbAttente' => (int)$nb_attente,
        'users' => $users,
        'filters' => [
            'member_id' => $request->member_id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'type' => $request->type,
        ],
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

        $user = auth()->user();
        $isAdmin = $user->isAdmin();

        Finance::create([
            'user_id' => $user->id,
            'montant' => $request->montant,
            'type' => 'cotisation',
            'statut_valide' => $isAdmin, // Auto-validate if admin
            'description' => $request->description,
        ]);

        $message = $isAdmin
            ? 'Dépôt effectué et validé automatiquement.'
            : 'Dépôt effectué avec succès ! Attente validation admin.';

        return back()->with('success', $message);
    }

    // Validation par admin
    public function valider($id)
    {
        $finance = Finance::findOrFail($id);
        $this->authorize('valider', $finance);
        $finance->update(['statut_valide' => true]);
        return back()->with('success', 'Dépôt validé.');
    }

    // Validation en masse des dépôts (admin)
    public function validerTous()
    {
        $pending = Finance::where('statut_valide', false)
            ->where('type', 'cotisation');

        $this->authorize('validerTous', Finance::class);

        $count = $pending->count();
        if ($count > 0) {
            $pending->update(['statut_valide' => true]);
        }

        return redirect()->route('finances.index')->with('success', "{$count} dépôt(s) validé(s).");
    }

    public function createDepense()
    {
        return Inertia::render('Finance/FinanceCreateDepense');
    }

    public function storeDepense(Request $request)
    {
        $this->authorize('createDepense', Finance::class);

        $request->validate([
            'montant' => 'required|numeric|min:100|max:100000',
            'description' => 'required|string',
        ]);

        // Détermine validation immédiate ou non via la policy
        $statutValide = $request->user()->can('autoValideDepense', Finance::class);

        Finance::create([
            'user_id' => auth()->id(),
            'montant' => $request->montant,
            'type' => 'dépense',
            'statut_valide' => $statutValide,
            'description' => $request->description,
        ]);

        $message = $statutValide
            ? 'Dépense ajoutée et validée.'
            : 'Dépense ajoutée. En attente de validation par l\administrateur.';

        return back()->with('success', $message);
    }

    public function createAjustement()
    {
        $this->authorize('ajustement', Finance::class); // à définir dans ta policy
        return Inertia::render('Finance/FinanceCreateAjustement');
    }

    // Enregistrement ajustement manuel (admin)
    public function storeAjustement(Request $request)
    {
        $this->authorize('ajustement', Finance::class);

        $request->validate([
            'montant' => 'required|numeric|not_in:0',
            'sens' => 'required|in:credit,debit',
            'description' => 'required|string|max:255',
        ]);

        $montant = $request->sens === 'credit'
            ? abs($request->montant)
            : -abs($request->montant);

        Finance::create([
            'user_id' => auth()->id(),
            'montant' => $montant,
            'type' => 'ajustement',
            'statut_valide' => true,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Ajustement manuel enregistré avec succès.');
    }
}
