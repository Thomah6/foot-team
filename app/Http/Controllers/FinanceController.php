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

        // Filtres
        if ($request->member_id)
            $query->where('user_id', $request->member_id);
        if ($request->date_from)
            $query->where('created_at', '>=', $request->date_from);
        if ($request->date_to)
            $query->where('created_at', '<=', $request->date_to);
        if ($request->type)
            $query->where('type', $request->type);
        if ($request->statut_valide !== null)
            $query->where('statut_valide', $request->statut_valide);
        

        $finances = $query->orderByDesc('created_at')->get();

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
        
        $nb_attente = Finance::where('statut_valide', false)
            ->where('type', 'cotisation')->count();
        
            $users = User::all();

            // Return props using camelCase keys expected by the Vue components
            return Inertia::render('Finance/Index', [
                'finances' => $finances->toArray(),
                'soldeCotisations' => $solde_cotisations,
                'soldeDepenses' => $solde_depenses,
                'soldeTotal' => $solde_total,
                'totalAttente' => $total_attente,
                'nbAttente' => $nb_attente,
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

        return back()->with('success', "{$count} dépôt(s) validé(s).");
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

    return redirect()->route('finances.index')
        ->with('success', 'Ajustement manuel enregistré avec succès.');
}
}





