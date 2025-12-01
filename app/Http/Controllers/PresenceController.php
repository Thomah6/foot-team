<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PresenceController extends Controller
{
    /**
     * Afficher la page de gestion des présences
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';
        $isBureau = $user->role === 'bureau';
        $selectedDate = $request->query('date') ? Carbon::parse($request->query('date')) : Carbon::now();
        $searchQuery = $request->query('search', '');
        $month = $request->query('month') ? Carbon::parse($request->query('month')) : Carbon::now();

        // Récupérer les utilisateurs de l'équipe (filtrés si nécessaire)
        $usersQuery = User::query();
        
        if ($searchQuery) {
            $usersQuery->where('name', 'like', '%' . $searchQuery . '%')
                       ->orWhere('email', 'like', '%' . $searchQuery . '%');
        }

        $users = $usersQuery->get();

        // Récupérer les dates de présence du mois
        $startOfMonth = $month->clone()->startOfMonth();
        $endOfMonth = $month->clone()->endOfMonth();

        $presences = Presence::with('declaredByUser')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function ($presence) {
                // $presence->date est déjà une string (YYYY-MM-DD)
                return is_string($presence->date) ? $presence->date : $presence->date->toDateString();
            });

        // Récupérer les présences du jour sélectionné (pour le filtre calendrier)
        $dayPresences = Presence::whereDate('date', $selectedDate)
            ->with(['user', 'declaredByUser'])
            ->get();

        // Données pour le tableau
        $presenceData = $this->buildPresenceTableData($users, $presences, $month);

        return Inertia::render('Presence/Index', [
            'presenceData' => $presenceData,
            'dayPresences' => $dayPresences,
            'selectedDate' => $selectedDate->toDateString(),
            'month' => $month->toDateString(),
            'searchQuery' => $searchQuery,
            'isAdmin' => $isAdmin,
            'isBureau' => $isBureau,
            'currentUserId' => $user->id,
            'users' => $isBureau ? $users : [], // Bureau voit la liste des joueurs pour déclarer
            'monthDates' => $this->getPresenceDatesInMonth($month),
        ]);
    }

    /**
     * Afficher l'historique des présences
     */
    public function history(Request $request)
    {
        $isAdmin = auth()->user()->role === 'admin';
        $userId = $isAdmin ? $request->query('user_id') : auth()->id();
        $month = $request->query('month') ? Carbon::parse($request->query('month')) : Carbon::now();

        $startOfMonth = $month->clone()->startOfMonth();
        $endOfMonth = $month->clone()->endOfMonth();

        if ($isAdmin) {
            // Construire la requête de base
            $query = Presence::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->with(['user', 'declaredByUser'])
                ->orderBy('date', 'desc');

            // Si un utilisateur spécifique est sélectionné, le filtrer
            if ($userId) {
                $query->where('user_id', $userId);
            }

            $presences = $query->get();

            // Grouper par nom d'utilisateur uniquement si tous les utilisateurs sont affichés
            if ($userId) {
                $presenceHistory = $presences;
            } else {
                $presenceHistory = $presences->groupBy(function ($presence) {
                    return $presence->user->name;
                });
            }

            $users = User::all();
        } else {
            // L'utilisateur voit son propre historique
            $presenceHistory = Presence::where('user_id', auth()->id())
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->with('declaredByUser')
                ->orderBy('date', 'desc')
                ->get();

            $users = [];
        }

        return Inertia::render('Presence/History', [
            'presenceHistory' => $presenceHistory,
            'month' => $month->toDateString(),
            'isAdmin' => $isAdmin,
            'users' => $users,
        ]);
    }

    /**
     * Déclarer sa présence (ou celle d'un autre si bureau)
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $isBureau = $user->role === 'bureau';
        $isAdmin = $user->role === 'admin';

        // Debug log to help trace bureau submissions
        if ($isBureau) {
            Log::info('Presence store called by bureau', [
                'user_id' => $user->id,
                'request_all' => $request->all(),
                'headers' => [
                    'accept' => $request->header('Accept'),
                    'x-requested-with' => $request->header('X-Requested-With'),
                ],
            ]);
        }

        // Validation
        if ($isBureau) {
            $validated = $request->validate([
                'date' => 'required|date',
                'user_id' => 'required|exists:users,id', // Bureau doit spécifier pour qui
            ]);
            $targetUserId = $validated['user_id'];
        } else {
            $validated = $request->validate([
                'date' => 'required|date',
            ]);
            $targetUserId = $user->id;
        }

        $date = Carbon::parse($validated['date'])->startOfDay();

        // Vérifier si une présence existe déjà pour ce jour (comparer via date string)
        $existingPresence = Presence::where('user_id', $targetUserId)
            ->where('date', $date->toDateString())
            ->first();

        if ($existingPresence) {
            $message = $isBureau ? 'Cette personne a déjà une déclaration pour ce jour.' : 'Vous avez déjà déclaré votre présence pour ce jour.';
            return back()->with('error', $message);
        }

        // Créer la présence
        $presence = Presence::create([
            'user_id' => $targetUserId,
            'date' => $date->toDateString(),
            'present' => true,
            'validated_by_admin' => $isAdmin, // auto-validate si admin
            'declared_by_user_id' => $isBureau ? $user->id : null, // Tracer qui a déclaré si bureau
        ]);

        if ($isBureau) {
            Log::info('Presence created by bureau', ['presence_id' => $presence->id, 'declared_by' => $presence->declared_by_user_id]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $isAdmin ? 'Présence déclarée et validée automatiquement.' : 'Présence déclarée avec succès. En attente de validation admin.',
                'presence' => [
                    'id' => $presence->id,
                    'user_id' => $presence->user_id,
                    'date' => $presence->date,
                    'present' => $presence->present,
                    'validated' => $presence->validated_by_admin,
                    'declared_by_user_id' => $presence->declared_by_user_id,
                    'declared_by_user_name' => $presence->declaredByUser ? $presence->declaredByUser->name : null,
                ],
            ], 201);
        }

        return back()->with('success', $isAdmin ? 'Présence déclarée et validée automatiquement.' : 'Présence déclarée avec succès. En attente de validation admin.');
    }

    /**
     * Valider une présence (admin uniquement)
     */
    public function validate(Request $request, Presence $presence)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Accès non autorisé');
        }

        $validated = $request->validate([
            'validated' => 'required|boolean',
        ]);

        $presence->update([
            'validated_by_admin' => $validated['validated'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Présence mise à jour avec succès.',
                'presence' => [
                    'id' => $presence->id,
                    'validated' => $presence->validated_by_admin,
                ],
            ]);
        }

        return back()->with('success', 'Présence mise à jour avec succès.');
    }

    /**
     * Mettre à jour une présence (admin peut modifier)
     */
    public function update(Request $request, Presence $presence)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Accès non autorisé');
        }

        $validated = $request->validate([
            'present' => 'required|boolean',
            'validated_by_admin' => 'required|boolean',
        ]);

        $presence->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Présence mise à jour avec succès.',
                'presence' => [
                    'id' => $presence->id,
                    'present' => $presence->present,
                    'validated' => $presence->validated_by_admin,
                ],
            ]);
        }

        return back()->with('success', 'Présence mise à jour avec succès.');
    }

    /**
     * Récupérer les présences par date (API)
     */
    public function getByDate(Request $request)
    {
        $date = Carbon::parse($request->query('date'))->startOfDay();

        $presences = Presence::whereDate('date', $date)
            ->with('user')
            ->get()
            ->map(function ($presence) {
                return [
                    'id' => $presence->id,
                    'user_name' => $presence->user->name,
                    'present' => $presence->present,
                    'validated' => $presence->validated_by_admin,
                ];
            });

        return response()->json($presences);
    }

    /**
     * Rapport mensuel des présences
     */
    public function monthlyReport(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Accès non autorisé');
        }

        $month = $request->query('month') ? Carbon::parse($request->query('month')) : Carbon::now();
        $startOfMonth = $month->clone()->startOfMonth();
        $endOfMonth = $month->clone()->endOfMonth();

        $users = User::all();
        $report = [];

        foreach ($users as $user) {
            $presences = Presence::where('user_id', $user->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();

            $present = $presences->where('present', true)->count();
            $validated = $presences->where('validated_by_admin', true)->count();
            $absent = $presences->where('present', false)->count();

            $report[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'present' => $present,
                'absent' => $absent,
                'validated' => $validated,
                'percentage' => $presences->count() > 0 ? round(($present / $presences->count()) * 100, 2) : 0,
            ];
        }

        return Inertia::render('Presence/MonthlyReport', [
            'report' => $report,
            'month' => $month->toDateString(),
        ]);
    }

    /**
     * Construire les données du tableau de présences
     */
    private function buildPresenceTableData($users, $presences, $month = null)
    {
        // Obtenir tous les jeudis du mois
        $thursdayDates = $this->getPresenceDatesInMonth($month);
        
        $tableData = [];

        foreach ($users as $user) {
            $row = [
                'id' => $user->id,
                'name' => $user->name,
                'presences' => [],
            ];

            // Pour chaque jeudi du mois, chercher une présence ou laisser null
            foreach ($thursdayDates as $date) {
                // Chercher dans les présences groupées par date
                $userPresence = null;
                if (isset($presences[$date])) {
                    $userPresence = $presences[$date]->where('user_id', $user->id)->first();
                }

                $row['presences'][$date] = $userPresence ? [
                    'id' => $userPresence->id,
                    'present' => $userPresence->present,
                    'validated' => $userPresence->validated_by_admin,
                    'declared_by_user_id' => $userPresence->declared_by_user_id,
                    'declared_by_user_name' => $userPresence->declaredByUser ? $userPresence->declaredByUser->name : null,
                ] : null;
            }

            $tableData[] = $row;
        }

        return $tableData;
    }

    /**
     * Obtenir tous les jeudis du mois (jours de jeu)
     */
    private function getPresenceDatesInMonth($month = null)
    {
        if (!$month) {
            $month = Carbon::now();
        }
        
        $startOfMonth = $month->clone()->startOfMonth();
        $endOfMonth = $month->clone()->endOfMonth();
        
        $thursdayDates = [];
        $current = $startOfMonth->copy();
        
        // Aller au premier jeudi du mois
        while ($current->dayOfWeek !== 4) { // 4 = jeudi
            $current->addDay();
        }
        
        // Ajouter tous les jeudis du mois
        while ($current->lte($endOfMonth)) {
            $thursdayDates[] = $current->toDateString();
            $current->addWeek();
        }
        
        return $thursdayDates;
    }
}
