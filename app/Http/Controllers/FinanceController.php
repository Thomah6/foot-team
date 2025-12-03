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

        $finances = $query
            ->orderByDesc('created_at')
            ->paginate(10)
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

        $solde_total = $this->getSoldeTotal();

        $solde_cotisations = Finance::where('type', 'cotisation')->where('statut_valide', true)->sum('montant');
        $solde_depenses = Finance::where('type', 'dépense')->where('statut_valide', true)->sum('montant');

        $total_attente = Finance::where('statut_valide', false)->where('type', 'cotisation')->sum('montant');
        $nb_attente = Finance::where('statut_valide', false)->where('type', 'cotisation')->count();
        $pending_depenses_total = Finance::where('statut_valide', false)->where('type', 'dépense')->sum('montant');
        $pending_depenses_count = Finance::where('statut_valide', false)->where('type', 'dépense')->count();
        $users = User::orderBy('name')->get(['id', 'name']);

        $finances->appends($request->all());

        return Inertia::render('Finance/Index', [
            'finances' => $finances,
            'soldeCotisations' => (int)$solde_cotisations,
            'soldeDepenses' => (int)$solde_depenses,
            'soldeTotal' => (int)$solde_total,
            'totalAttente' => (int)$total_attente,
            'nbAttente' => (int)$nb_attente,
            'pendingDepensesTotal' => (int)$pending_depenses_total,
            'pendingDepensesCount' => (int)$pending_depenses_count,
            'users' => $users,
            'filters' => $request->only(['member_id', 'date_from', 'date_to', 'type', 'statut_valide']),
        ]);
    }

    private function getSoldeTotal()
    {
        $cotisations = Finance::where('type', 'cotisation')->where('statut_valide', true)->sum('montant');
        $depenses = Finance::where('type', 'dépense')->where('statut_valide', true)->sum('montant');
        $ajustements = Finance::where('type', 'ajustement')->where('statut_valide', true)->sum('montant');
        return $cotisations - $depenses + $ajustements;
    }

    public function createDepot()
    {
        return Inertia::render('Finance/CreateDepot');
    }

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
            'statut_valide' => $isAdmin,
            'description' => $request->description,
        ]);

        $message = $isAdmin ? 'Dépôt effectué et validé.' : 'Dépôt en attente de validation.';
        return back()->with('success', $message);
    }

    public function valider($id)
    {
        $finance = Finance::findOrFail($id);
        $this->authorize('valider', $finance);
        $finance->update(['statut_valide' => true]);
        return back()->with('success', 'Transaction validée.');
    }

    public function validerTous(Request $request)
    {
        $this->authorize('validerTous', Finance::class);
        $type = $request->input('type', 'cotisation');
        $count = Finance::where('statut_valide', false)->where('type', $type)->update(['statut_valide' => true]);
        $label = $type === 'dépense' ? 'dépense(s)' : 'dépôt(s)';
        return back()->with('success', "{$count} {$label} validé(s).");
    }

    public function createDepense()
    {
        return Inertia::render('Finance/FinanceCreateDepense', [
            'soldeTotal' => $this->getSoldeTotal()
        ]);
    }

    public function storeDepense(Request $request)
    {
        $this->authorize('createDepense', Finance::class);

        if ($this->getSoldeTotal() <= 0) {
            return redirect()->route('finances.index')->with('error', 'Le solde est insuffisant pour enregistrer une nouvelle dépense.');
        }

        $request->validate([
            'montant' => 'required|numeric|min:100|max:100000',
            'description' => 'required|string',
        ]);

        $statutValide = $request->user()->can('autoValideDepense', Finance::class);

        Finance::create([
            'user_id' => auth()->id(),
            'montant' => $request->montant,
            'type' => 'dépense',
            'statut_valide' => $statutValide,
            'description' => $request->description,
        ]);

        $message = $statutValide ? 'Dépense ajoutée et validée.' : 'Dépense ajoutée en attente de validation.';
        return redirect()->route('finances.index')->with('success', $message);
    }

    public function createAjustement()
    {
        $this->authorize('ajustement', Finance::class);
        return Inertia::render('Finance/FinanceCreateAjustement', [
            'soldeTotal' => $this->getSoldeTotal()
        ]);
    }

    public function storeAjustement(Request $request)
    {
        $this->authorize('ajustement', Finance::class);

        $request->validate([
            'montant' => 'required|numeric|not_in:0',
            'sens' => 'required|in:credit,debit',
            'description' => 'required|string|max:255',
        ]);

        $montant = $request->sens === 'credit' ? abs($request->montant) : -abs($request->montant);

        if ($this->getSoldeTotal() <= 0 && $montant < 0) {
            return back()->with('error', 'Le solde est insuffisant pour un ajustement en débit.');
        }

        Finance::create([
            'user_id' => auth()->id(),
            'montant' => $montant,
            'type' => 'ajustement',
            'statut_valide' => true,
            'description' => $request->description,
        ]);

        return redirect()->route('finances.index')->with('success', 'Ajustement enregistré.');
    }
}
