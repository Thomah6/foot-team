Galerie: date de création de la photo , galerie interactive, garder le nom de l'utilisateur qui a uploadé la photo

Un membre peut faire des suggestions , l'admin peut transformer la suggestion en réflexion
Savoir qui a suggéré :texte de la suggestion , la date de création de la suggestion et l'auteur de la suggestion*


L'admin peut valider une réflexion selon le nombre de votes pour chaque vote pour ou contre ,


Connexion grâce à google,il peut 

ETAPE 1: Créer le teamStatController

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamStat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamStatController extends Controller
{
    /**
     * Afficher la liste de toutes les stats des équipes
     */
    public function index()
    {
        $stats = TeamStat::with('team')
            ->orderBy('month', 'desc')
            ->orderBy('team_id')
            ->paginate(15);

        return Inertia::render('Admin/TeamStats/Index', [
            'stats' => $stats,
            'teamsCount' => Team::count(),
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $teams = Team::all(['id', 'name']);

        return Inertia::render('Admin/TeamStats/CreateEdit', [
            'teams' => $teams,
            'stat' => null,
            'isEditing' => false,
            'currentMonth' => now()->startOfMonth()->toDateString(),
        ]);
    }

    /**
     * Stocker une nouvelle stat d'équipe
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'wins' => 'required|integer|min:0',
            'losses' => 'required|integer|min:0',
            'draws' => 'required|integer|min:0',
            'month' => 'required|date_format:Y-m-d',
        ]);

        // Convertir la date pour extraire le mois/année
        $monthDate = Carbon::parse($validated['month']);
        $monthStart = $monthDate->startOfMonth()->toDateString();
        $monthEnd = $monthDate->endOfMonth()->toDateString();

        // Vérifier qu'il n'existe pas déjà une stat pour cette équipe ce mois
        $existingMonth = TeamStat::where('team_id', $validated['team_id'])
            ->whereBetween('month', [$monthStart, $monthEnd])
            ->first();

        if ($existingMonth && !$request->has('override')) {
            return back()->withErrors([
                'duplicate' => 'Une stat existe déjà pour cette équipe ce mois-ci. Voulez-vous la remplacer ?',
            ]);
        }

        // Si override, supprimer l'ancien
        if ($existingMonth && $request->has('override')) {
            $existingMonth->delete();
        }

        TeamStat::create($validated);

        return redirect()->route('admin.team-stats.index')
            ->with('success', 'Stat d\'équipe créée avec succès');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(TeamStat $teamStat)
    {
        $teams = Team::all(['id', 'name']);

        return Inertia::render('Admin/TeamStats/CreateEdit', [
            'teams' => $teams,
            'stat' => $teamStat,
            'isEditing' => true,
            'currentMonth' => now()->startOfMonth()->toDateString(),
        ]);
    }

    /**
     * Mettre à jour une stat d'équipe
     */
    public function update(Request $request, TeamStat $teamStat)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'wins' => 'required|integer|min:0',
            'losses' => 'required|integer|min:0',
            'draws' => 'required|integer|min:0',
            'month' => 'required|date_format:Y-m-d',
        ]);

        $teamStat->update($validated);

        return redirect()->route('admin.team-stats.index')
            ->with('success', 'Stat d\'équipe mise à jour avec succès');
    }

    /**
     * Supprimer une stat d'équipe
     */
    public function destroy(TeamStat $teamStat)
    {
        $teamName = $teamStat->team->name;
        $month = Carbon::parse($teamStat->month)->format('F Y');

        $teamStat->delete();

        return redirect()->route('admin.team-stats.index')
            ->with('success', "Stat de {$teamName} ({$month}) supprimée");
    }

    /**
     * Afficher les stats filtrées par équipe
     */
    public function byTeam(Team $team)
    {
        $stats = $team->stats()
            ->orderBy('month', 'desc')
            ->get();

        return Inertia::render('Admin/TeamStats/TeamHistory', [
            'team' => $team,
            'stats' => $stats,
        ]);
    }

    /**
     * Obtenir les stats du mois courant par équipe
     */
    public function currentMonthStats()
    {
        $currentMonth = now()->startOfMonth();

        $stats = TeamStat::with('team')
            ->where('month', $currentMonth->toDateString())
            ->orderBy('team_id')
            ->get()
            ->map(function ($stat) {
                return [
                    'team_id' => $stat->team_id,
                    'team_name' => $stat->team->name,
                    'wins' => $stat->wins,
                    'losses' => $stat->losses,
                    'draws' => $stat->draws,
                    'total_matches' => $stat->total_matches,
                    'points' => $stat->points,
                    'win_rate' => $stat->win_rate,
                ];
            });

        return response()->json($stats);
    }

    /**
     * Validation en masse des stats
     */
    public function bulkValidate(Request $request)
    {
        $validated = $request->validate([
            'stat_ids' => 'required|array',
            'stat_ids.*' => 'exists:team_stats,id',
        ]);

        TeamStat::whereIn('id', $validated['stat_ids'])->update(['validated' => true]);

        return back()->with('success', 'Stats validées avec succès');
    }
}


ETAPE 2 : Gérer les relations entre la table team et la table team_stats au niveau des modèles Team , TeamStat.php

TEAM.PHP
   public function stats()
    {
        return $this->hasMany(TeamStat::class);
    }

    // Obtenir les stats du mois courant
    public function currentMonthStat()
    {
        $currentMonthStart = now()->startOfMonth();
        return $this->stats()
            ->where('month', $currentMonthStart->toDateString())
            ->first();
    }

    // Obtenir les stats du dernier mois
    public function lastMonthStat()
    {
        return $this->stats()
            ->orderBy('month', 'desc')
            ->first();
    }

TEAMSTAT.PHP

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TeamStat extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'wins', 'losses', 'draws', 'month'];

    protected $casts = [
        'month' => 'date',
        'wins' => 'integer',
        'losses' => 'integer',
        'draws' => 'integer',
    ];

    // Relations
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Accessors
    public function getTotalMatchesAttribute()
    {
        return $this->wins + $this->losses + $this->draws;
    }

    public function getPointsAttribute()
    {
        return ($this->wins * 3) + $this->draws;
    }

    public function getWinRateAttribute()
    {
        if ($this->total_matches === 0) return 0;
        return round(($this->wins / $this->total_matches) * 100, 2);
    }

    // Scopes
    public function scopeByTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeByMonth($query, $month)
    {
        return $query->where('month', $month);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('month', 'desc');
    }
}

ETAPE 4:Ajouter la colonne validated dans la migration team_stats_table

$table->boolean('validated')->default(true);


ETAPE 5 : Créer le helper pour afficher les stats par équipe:
TeamStatHelper.php

<?php

namespace App\Helpers;

use App\Models\TeamStat;

class TeamStatHelper
{
    /**
     * Obtenir les classements des équipes pour le mois courant
     */
    public static function getMonthlyRankings()
    {
        $currentMonth = now()->startOfMonth()->toDateString();

        return TeamStat::with('team')
            ->where('month', $currentMonth)
            ->orderByDesc('wins')
            ->orderByDesc('draws')
            ->orderByDesc('losses')
            ->get()
            ->map(function ($stat) {
                return [
                    'team' => $stat->team,
                    'wins' => $stat->wins,
                    'losses' => $stat->losses,
                    'draws' => $stat->draws,
                    'total_matches' => $stat->total_matches,
                    'points' => $stat->points,
                    'win_rate' => $stat->win_rate,
                ];
            });
    }

    /**
     * Obtenir les 5 meilleures équipes de tous les temps
     */
    public static function getTopTeams($limit = 5)
    {
        return TeamStat::selectRaw(
            'team_id, 
             SUM(wins) as total_wins, 
             SUM(losses) as total_losses, 
             SUM(draws) as total_draws,
             (SUM(wins) * 3 + SUM(draws)) as total_points'
        )
            ->groupBy('team_id')
            ->with('team')
            ->orderByDesc('total_points')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les performances d'une équipe
     */
    public static function getTeamPerformance($teamId)
    {
        $stats = TeamStat::where('team_id', $teamId)
            ->orderBy('month', 'desc')
            ->get();

        return [
            'total_wins' => $stats->sum('wins'),
            'total_losses' => $stats->sum('losses'),
            'total_draws' => $stats->sum('draws'),
            'total_points' => $stats->sum(fn ($s) => ($s->wins * 3) + $s->draws),
            'average_win_rate' => $stats->isEmpty() ? 0 : round(
                $stats->sum('wins') / 
                $stats->sum(fn ($s) => $s->wins + $s->losses + $s->draws) * 100,
                2
            ),
        ];
    }

    /**
     * Formater une date de mois
     */
    public static function formatMonth($date)
    {
        return \Carbon\Carbon::parse($date)->isoFormat('MMMM YYYY');
    }
}

ETAPE MII CHEMIN OUBLIée:FAIRE LES ROUTES DANS WEB.PHP
WEB.PHP


// Routes admin avec authentification
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/stats', [StatController::class, 'index'])
            ->name('stats.index');

        Route::get('/stats/pending', [StatController::class, 'pending'])
            ->name('stats.pending');

        Route::post('/stats', [StatController::class, 'store'])
            ->name('stats.store');

        Route::post('/stats/{stat}/validate', [StatController::class, 'validateStat'])
            ->name('stats.validate');

        Route::delete('/stats/{stat}/reject', [StatController::class, 'rejectStat'])
            ->name('stats.reject');

        // 🏆 Routes pour les stats d'équipes
        Route::get('/team-stats', [TeamStatController::class, 'index'])
            ->name('team-stats.index');

        Route::get('/team-stats/create', [TeamStatController::class, 'create'])
            ->name('team-stats.create');

        Route::post('/team-stats', [TeamStatController::class, 'store'])
            ->name('team-stats.store');

        Route::get('/team-stats/{teamStat}/edit', [TeamStatController::class, 'edit'])
            ->name('team-stats.edit');

        Route::patch('/team-stats/{teamStat}', [TeamStatController::class, 'update'])
            ->name('team-stats.update');

        Route::delete('/team-stats/{teamStat}', [TeamStatController::class, 'destroy'])
            ->name('team-stats.destroy');

        Route::get('/team-stats/team/{team}', [TeamStatController::class, 'byTeam'])
            ->name('team-stats.by-team');

        Route::get('/team-stats/api/current-month', [TeamStatController::class, 'currentMonthStats'])
            ->name('team-stats.current-month-api');

        Route::post('/team-stats/bulk-validate', [TeamStatController::class, 'bulkValidate'])
            ->name('team-stats.bulk-validate');
    });




ETAPE 6: Créer la vue pour afficher les stats par équipe
TeamStatsClassement.vue

<template>
  <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
      <h2 class="text-2xl md:text-3xl font-bold text-white">
        🏆 Classement des Équipes
      </h2>
      <p class="text-blue-100 mt-1">
        {{ monthLabel }}
      </p>
    </div>

    <!-- Contenu -->
    <div v-if="stats.length > 0" class="overflow-x-auto">
      <!-- Version desktop -->
      <table class="w-full hidden md:table">
        <thead>
          <tr class="bg-gray-100 dark:bg-gray-800">
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 dark:text-white">
              Rang
            </th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 dark:text-white">
              Équipe
            </th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white">
              V/D/N
            </th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white">
              Matchs
            </th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white">
              Points
            </th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white">
              Taux V.
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(stat, index) in stats"
            :key="stat.team.id"
            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition"
            :class="{ 'bg-yellow-50 dark:bg-yellow-900': index === 0 }"
          >
            <td class="px-6 py-4">
              <div class="text-lg font-bold text-gray-900 dark:text-white">
                {{ getMedal(index) }} {{ index + 1 }}
              </div>
            </td>
            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
              {{ stat.team.name }}
            </td>
            <td class="px-6 py-4">
              <div class="flex justify-center gap-2 text-sm font-medium">
                <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded">
                  {{ stat.wins }}V
                </span>
                <span class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded">
                  {{ stat.losses }}D
                </span>
                <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded">
                  {{ stat.draws }}N
                </span>
              </div>
            </td>
            <td class="px-6 py-4 text-center font-semibold text-gray-900 dark:text-white">
              {{ stat.total_matches }}
            </td>
            <td class="px-6 py-4 text-center font-bold text-blue-600 dark:text-blue-400">
              {{ stat.points }}
            </td>
            <td class="px-6 py-4 text-center">
              <div class="inline-block px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-full font-semibold">
                {{ stat.win_rate }}%
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Version mobile -->
      <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
        <div
          v-for="(stat, index) in stats"
          :key="stat.team.id"
          class="p-4"
          :class="{ 'bg-yellow-50 dark:bg-yellow-900': index === 0 }"
        >
          <div class="flex items-start justify-between mb-3">
            <div>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ getMedal(index) }}
              </p>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                Position {{ index + 1 }}
              </p>
            </div>
            <div class="text-right">
              <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                {{ stat.points }} pts
              </p>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ stat.win_rate }}% V
              </p>
            </div>
          </div>

          <p class="font-bold text-gray-900 dark:text-white mb-3">
            {{ stat.team.name }}
          </p>

          <div class="grid grid-cols-3 gap-2 mb-3">
            <div class="bg-green-100 dark:bg-green-900 p-2 rounded text-center">
              <p class="text-xs text-green-700 dark:text-green-300">Victoires</p>
              <p class="text-lg font-bold text-green-900 dark:text-green-100">
                {{ stat.wins }}
              </p>
            </div>
            <div class="bg-red-100 dark:bg-red-900 p-2 rounded text-center">
              <p class="text-xs text-red-700 dark:text-red-300">Défaites</p>
              <p class="text-lg font-bold text-red-900 dark:text-red-100">
                {{ stat.losses }}
              </p>
            </div>
            <div class="bg-yellow-100 dark:bg-yellow-900 p-2 rounded text-center">
              <p class="text-xs text-yellow-700 dark:text-yellow-300">Nuls</p>
              <p class="text-lg font-bold text-yellow-900 dark:text-yellow-100">
                {{ stat.draws }}
              </p>
            </div>
          </div>

          <div class="text-xs text-gray-600 dark:text-gray-400">
            {{ stat.total_matches }} matchs joués
          </div>
        </div>
      </div>
    </div>

    <!-- Message vide -->
    <div v-else class="text-center py-12">
      <p class="text-gray-600 dark:text-gray-400">
        Aucune stat disponible pour ce mois
      </p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  stats: {
    type: Array,
    required: true,
  },
});

const monthLabel = computed(() => {
  const now = new Date();
  return new Intl.DateTimeFormat('fr-FR', {
    month: 'long',
    year: 'numeric',
  }).format(now);
});

const getMedal = (index) => {
  const medals = ['🥇', '🥈', '🥉'];
  return medals[index] || '📍';
};
</script>



ETAPE 7:Créer le dossier TeamStat pour créer les vue CreateEdit,Form,Index,TeamHistory,

CREATEEDIT.VUE

<template>
  <div>
    <!-- Header -->
    <div class="mb-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
            {{ isEditing ? 'Modifier les stats' : 'Ajouter des stats' }}
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            {{ isEditing ? 'Mettez à jour les résultats de l\'équipe' : 'Enregistrez les nouveaux résultats de l\'équipe' }}
          </p>
        </div>
        <Link
          href="/admin/team-stats"
          class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300
                 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600
                 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
        >
          ← Retour
        </Link>
      </div>
    </div>

    <!-- Formulaire principal -->
    <form @submit.prevent="submit" class="space-y-6">
      <!-- Section Équipe -->
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 md:p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Équipe</h2>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Sélectionner l'équipe
          </label>
          <select
            v-model="form.team_id"
            :disabled="isEditing"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                   bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white
                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                   disabled:opacity-50 disabled:cursor-not-allowed transition"
          >
            <option value="">-- Choisir une équipe --</option>
            <option v-for="team in teams" :key="team.id" :value="team.id">
              {{ team.name }}
            </option>
          </select>
          <InputError :message="form.errors.team_id" class="mt-2" />
        </div>
      </div>

      <!-- Section Mois -->
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 md:p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Période</h2>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Mois concerné (premier jour du mois)
          </label>
          <input
            v-model="form.month"
            type="date"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                   bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white
                   focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
          />
          <InputError :message="form.errors.month" class="mt-2" />
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Les données statistiques couvriront tout le mois sélectionné
          </p>
        </div>
      </div>

      <!-- Section Résultats - Composant Form.vue -->
      <TeamStatsForm v-model="statsData" />

      <!-- Section Résumé -->
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900 dark:to-indigo-900 
                  rounded-lg shadow p-4 md:p-6 border border-blue-200 dark:border-blue-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
          <span class="text-xl mr-2">📊</span>
          Résumé des données
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          <div>
            <p class="text-xs text-gray-600 dark:text-gray-400">Victoires</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">
              {{ form.wins }}
            </p>
          </div>
          <div>
            <p class="text-xs text-gray-600 dark:text-gray-400">Défaites</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">
              {{ form.losses }}
            </p>
          </div>
          <div>
            <p class="text-xs text-gray-600 dark:text-gray-400">Nuls</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
              {{ form.draws }}
            </p>
          </div>
          <div>
            <p class="text-xs text-gray-600 dark:text-gray-400">Total</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
              {{ totalMatches }}
            </p>
          </div>
        </div>
      </div>

      <!-- Messages d'erreur -->
      <div v-if="form.errors.duplicate" class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 
                                               rounded-lg p-4">
        <p class="text-sm text-red-700 dark:text-red-200">
          ⚠️ {{ form.errors.duplicate }}
        </p>
        <button
          @click="form.post(route('admin.team-stats.store'), { data: { ...form, override: true } })"
          type="button"
          class="mt-2 text-sm text-red-700 dark:text-red-200 font-semibold hover:underline"
        >
          Remplacer les données existantes
        </button>
      </div>

      <!-- Boutons d'action -->
      <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
        <Link
          href="/admin/team-stats"
          class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300
                 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600
                 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition
                 text-center"
        >
          Annuler
        </Link>
        <button
          type="submit"
          :disabled="form.processing"
          class="px-4 py-2 text-sm font-medium text-white
                 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800
                 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="form.processing" class="flex items-center justify-center">
            <span class="animate-spin mr-2">⏳</span>
            Traitement...
          </span>
          <span v-else>
            {{ isEditing ? '✏️ Mettre à jour' : '✅ Ajouter les stats' }}
          </span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import TeamStatsForm from './Form.vue';

const props = defineProps({
  teams: Array,
  stat: Object,
  isEditing: Boolean,
  currentMonth: String,
});

const statsData = ref({
  wins: props.stat?.wins || 0,
  losses: props.stat?.losses || 0,
  draws: props.stat?.draws || 0,
});

const form = useForm({
  team_id: props.stat?.team_id || '',
  month: props.stat?.month || props.currentMonth,
  wins: props.stat?.wins || 0,
  losses: props.stat?.losses || 0,
  draws: props.stat?.draws || 0,
});

const totalMatches = computed(() => {
  return form.wins + form.losses + form.draws;
});

// Synchroniser les changements du composant enfant
watch(statsData, (newVal) => {
  form.wins = newVal.wins;
  form.losses = newVal.losses;
  form.draws = newVal.draws;
}, { deep: true });

const submit = () => {
  if (props.isEditing) {
    form.patch(route('admin.team-stats.update', props.stat.id));
  } else {
    form.post(route('admin.team-stats.store'));
  }
};
</script>

FORM.vue

<template>
  <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 md:p-6">
    <!-- Titre et description -->
    <div class="mb-6">
      <h2 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-2">
        Résultats de l'équipe
      </h2>
      <p class="text-sm text-gray-600 dark:text-gray-400">
        Saisissez les victoires, défaites et matchs nuls pour cette équipe
      </p>
    </div>

    <!-- Grille 3 colonnes responsive -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
      <!-- Victoires -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          <span class="text-green-600 dark:text-green-400 font-bold">✓</span> Victoires
        </label>
        <input
          v-model.number="form.wins"
          type="number"
          min="0"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white
                 focus:ring-2 focus:ring-green-500 focus:border-transparent
                 transition duration-200"
          @input="calculateStats"
        />
        <p v-if="form.wins" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
          {{ form.wins * 3 }} pts
        </p>
      </div>

      <!-- Défaites -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          <span class="text-red-600 dark:text-red-400 font-bold">✗</span> Défaites
        </label>
        <input
          v-model.number="form.losses"
          type="number"
          min="0"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white
                 focus:ring-2 focus:ring-red-500 focus:border-transparent
                 transition duration-200"
          @input="calculateStats"
        />
      </div>

      <!-- Nuls -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          <span class="text-yellow-600 dark:text-yellow-400 font-bold">=</span> Nuls
        </label>
        <input
          v-model.number="form.draws"
          type="number"
          min="0"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white
                 focus:ring-2 focus:ring-yellow-500 focus:border-transparent
                 transition duration-200"
          @input="calculateStats"
        />
        <p v-if="form.draws" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
          {{ form.draws }} pt{{ form.draws > 1 ? 's' : '' }}
        </p>
      </div>
    </div>

    <!-- Statistiques calculées -->
    <div v-if="totalMatches > 0" class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <!-- Total matchs -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded p-3">
          <p class="text-xs text-gray-600 dark:text-gray-400">Matchs</p>
          <p class="text-xl md:text-2xl font-bold text-blue-600 dark:text-blue-300">
            {{ totalMatches }}
          </p>
        </div>

        <!-- Points -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded p-3">
          <p class="text-xs text-gray-600 dark:text-gray-400">Points</p>
          <p class="text-xl md:text-2xl font-bold text-green-600 dark:text-green-300">
            {{ totalPoints }}
          </p>
        </div>

        <!-- Taux de victoire -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded p-3">
          <p class="text-xs text-gray-600 dark:text-gray-400">Victoires %</p>
          <p class="text-xl md:text-2xl font-bold text-purple-600 dark:text-purple-300">
            {{ winRate }}%
          </p>
        </div>

        <!-- Moyenne points/match -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 rounded p-3">
          <p class="text-xs text-gray-600 dark:text-gray-400">Moy pts/match</p>
          <p class="text-xl md:text-2xl font-bold text-orange-600 dark:text-orange-300">
            {{ avgPointsPerMatch }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  modelValue: Object,
});

const emit = defineEmits(['update:modelValue']);

const form = ref({
  wins: props.modelValue?.wins || 0,
  losses: props.modelValue?.losses || 0,
  draws: props.modelValue?.draws || 0,
});

const totalMatches = computed(() => {
  return form.value.wins + form.value.losses + form.value.draws;
});

const totalPoints = computed(() => {
  return (form.value.wins * 3) + form.value.draws;
});

const winRate = computed(() => {
  if (totalMatches.value === 0) return 0;
  return Math.round((form.value.wins / totalMatches.value) * 100);
});

const avgPointsPerMatch = computed(() => {
  if (totalMatches.value === 0) return 0;
  return (totalPoints.value / totalMatches.value).toFixed(2);
});

const calculateStats = () => {
  emit('update:modelValue', form.value);
};

// Synchroniser avec les changements de props
watch(props, (newProps) => {
  if (newProps.modelValue) {
    form.value = { ...newProps.modelValue };
  }
}, { deep: true });
</script>


INDEX.VUE


<template>
  <div>
    <!-- Header avec CTA -->
    <div class="mb-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
            📊 Stats des équipes
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Gérez les résultats des équipes pour chaque mois
          </p>
        </div>
        <Link
          href="/admin/team-stats/create"
          class="px-4 py-2.5 text-sm font-medium text-white
                 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800
                 rounded-lg transition flex items-center justify-center w-full sm:w-auto
                 shadow-md hover:shadow-lg"
        >
          ✅ Ajouter des stats
        </Link>
      </div>
    </div>

    <!-- Statistiques globales -->
    <div v-if="stats.data.length > 0" class="grid grid-cols-2 sm:grid-cols-4 gap-3 md:gap-4 mb-6">
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 border-blue-500">
        <p class="text-xs text-gray-600 dark:text-gray-400 uppercase">Total équipes</p>
        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
          {{ teamsCount }}
        </p>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 border-green-500">
        <p class="text-xs text-gray-600 dark:text-gray-400 uppercase">Stats</p>
        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
          {{ stats.data.length }}
        </p>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 border-yellow-500">
        <p class="text-xs text-gray-600 dark:text-gray-400 uppercase">Total V/D/N</p>
        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
          {{ calculateTotalMatches() }}
        </p>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 border-purple-500">
        <p class="text-xs text-gray-600 dark:text-gray-400 uppercase">Points</p>
        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
          {{ calculateTotalPoints() }}
        </p>
      </div>
    </div>

    <!-- Tableau stats - Version mobile friendly -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow overflow-hidden">
      <!-- Tableau desktop -->
      <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase">
                Équipe
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 dark:text-white uppercase">
                Mois
              </th>
              <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white uppercase">
                Résultats
              </th>
              <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white uppercase">
                Matchs
              </th>
              <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white uppercase">
                Points
              </th>
              <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white uppercase">
                Taux de V.
              </th>
              <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white uppercase">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="stat in stats.data"
              :key="stat.id"
              class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition"
            >
              <td class="px-6 py-4">
                <div class="font-semibold text-gray-900 dark:text-white">
                  {{ stat.team.name }}
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                {{ formatMonth(stat.month) }}
              </td>
              <td class="px-6 py-4">
                <div class="flex justify-center gap-2 text-sm font-medium">
                  <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded">
                    ✓ {{ stat.wins }}
                  </span>
                  <span class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded">
                    ✗ {{ stat.losses }}
                  </span>
                  <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded">
                    = {{ stat.draws }}
                  </span>
                </div>
              </td>
              <td class="px-6 py-4 text-center font-semibold text-gray-900 dark:text-white">
                {{ stat.wins + stat.losses + stat.draws }}
              </td>
              <td class="px-6 py-4 text-center font-semibold text-blue-600 dark:text-blue-400">
                {{ (stat.wins * 3) + stat.draws }}
              </td>
              <td class="px-6 py-4 text-center">
                <div class="inline-block px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-full text-sm font-semibold">
                  {{ ((stat.wins / (stat.wins + stat.losses + stat.draws)) * 100).toFixed(1) }}%
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex justify-center gap-2">
                  <Link
                    :href="`/admin/team-stats/${stat.id}/edit`"
                    class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium"
                  >
                    ✏️ Éditer
                  </Link>
                  <button
                    @click="deleteStat(stat.id)"
                    class="text-red-600 dark:text-red-400 hover:underline text-sm font-medium"
                  >
                    🗑️ Supprimer
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Cartes mobile -->
      <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
        <div v-for="stat in stats.data" :key="stat.id" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
          <!-- En-tête de la carte -->
          <div class="flex justify-between items-start mb-3">
            <div>
              <h3 class="font-bold text-gray-900 dark:text-white">
                {{ stat.team.name }}
              </h3>
              <p class="text-xs text-gray-600 dark:text-gray-400">
                {{ formatMonth(stat.month) }}
              </p>
            </div>
            <div class="text-right">
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Matchs</p>
              <p class="text-xl font-bold text-gray-900 dark:text-white">
                {{ stat.wins + stat.losses + stat.draws }}
              </p>
            </div>
          </div>

          <!-- Résultats -->
          <div class="grid grid-cols-3 gap-2 mb-3">
            <div class="text-center p-2 bg-green-100 dark:bg-green-900 rounded">
              <p class="text-xs text-green-700 dark:text-green-300">Victoires</p>
              <p class="text-lg font-bold text-green-900 dark:text-green-100">
                {{ stat.wins }}
              </p>
            </div>
            <div class="text-center p-2 bg-red-100 dark:bg-red-900 rounded">
              <p class="text-xs text-red-700 dark:text-red-300">Défaites</p>
              <p class="text-lg font-bold text-red-900 dark:text-red-100">
                {{ stat.losses }}
              </p>
            </div>
            <div class="text-center p-2 bg-yellow-100 dark:bg-yellow-900 rounded">
              <p class="text-xs text-yellow-700 dark:text-yellow-300">Nuls</p>
              <p class="text-lg font-bold text-yellow-900 dark:text-yellow-100">
                {{ stat.draws }}
              </p>
            </div>
          </div>

          <!-- Statistiques complémentaires -->
          <div class="grid grid-cols-2 gap-2 mb-3">
            <div class="bg-blue-50 dark:bg-blue-900 p-2 rounded">
              <p class="text-xs text-blue-700 dark:text-blue-300">Points</p>
              <p class="text-lg font-bold text-blue-900 dark:text-blue-100">
                {{ (stat.wins * 3) + stat.draws }}
              </p>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900 p-2 rounded">
              <p class="text-xs text-purple-700 dark:text-purple-300">Taux V.</p>
              <p class="text-lg font-bold text-purple-900 dark:text-purple-100">
                {{ ((stat.wins / (stat.wins + stat.losses + stat.draws)) * 100).toFixed(1) }}%
              </p>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
            <Link
              :href="`/admin/team-stats/${stat.id}/edit`"
              class="flex-1 text-center px-3 py-2 text-sm font-medium text-white
                     bg-blue-600 hover:bg-blue-700 rounded transition"
            >
              ✏️ Éditer
            </Link>
            <button
              @click="deleteStat(stat.id)"
              class="flex-1 px-3 py-2 text-sm font-medium text-white
                     bg-red-600 hover:bg-red-700 rounded transition"
            >
              🗑️ Supprimer
            </button>
          </div>
        </div>
      </div>

      <!-- Message vide -->
      <div v-if="stats.data.length === 0" class="text-center py-12">
        <p class="text-gray-600 dark:text-gray-400 mb-4">
          Aucune stat d'équipe enregistrée pour le moment
        </p>
        <Link
          href="/admin/team-stats/create"
          class="inline-block px-4 py-2 text-sm font-medium text-white
                 bg-green-600 hover:bg-green-700 rounded-lg transition"
        >
          ➕ Ajouter la première stat
        </Link>
      </div>
    </div>

    <!-- Pagination (si desktop) -->
    <div v-if="stats.links" class="mt-6 flex justify-between items-center">
      <p class="text-sm text-gray-600 dark:text-gray-400">
        Affichage {{ stats.from }} à {{ stats.to }} sur {{ stats.total }} stats
      </p>
      <div class="flex gap-2">
        <Link
          v-for="link in stats.links"
          :key="link.label"
          :href="link.url || '#'"
          :class="[
            'px-3 py-1 text-sm rounded transition',
            link.active
              ? 'bg-blue-600 text-white'
              : link.url
              ? 'text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-800'
              : 'text-gray-400 cursor-not-allowed'
          ]"
          v-html="link.label"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  stats: Object,
  teamsCount: Number,
});

const form = useForm({});

const formatMonth = (date) => {
  return new Intl.DateTimeFormat('fr-FR', {
    month: 'long',
    year: 'numeric',
  }).format(new Date(date));
};

const calculateTotalMatches = () => {
  return props.stats.data.reduce((sum, stat) => {
    return sum + stat.wins + stat.losses + stat.draws;
  }, 0);
};

const calculateTotalPoints = () => {
  return props.stats.data.reduce((sum, stat) => {
    return sum + (stat.wins * 3) + stat.draws;
  }, 0);
};

const deleteStat = (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette stat ?')) {
    form.delete(route('admin.team-stats.destroy', id), {
      onSuccess: () => {
        alert('Stat supprimée avec succès');
      },
    });
  }
};
</script>


TEAMHISTORY.VUE

<template>
  <div>
    <!-- Header -->
    <div class="mb-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
            📈 Historique: {{ team.name }}
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Consultez l'évolution des performances mensuelles
          </p>
        </div>
        <Link
          href="/admin/team-stats"
          class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300
                 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600
                 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition
                 text-center w-full sm:w-auto"
        >
          ← Retour aux stats
        </Link>
      </div>
    </div>

    <!-- Statistiques globales de l'équipe -->
    <div v-if="stats.length > 0" class="grid grid-cols-2 sm:grid-cols-4 gap-3 md:gap-4 mb-6">
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 border-blue-500">
        <p class="text-xs text-gray-600 dark:text-gray-400 uppercase">Total V</p>
        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
          {{ calculateTotalWins() }}
        </p>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 border-red-500">
        <p class="text-xs text-gray-600 dark:text-gray-400 uppercase">Total D</p>
        <p class="text-2xl font-bold text-red-600 dark:text-red-400">
          {{ calculateTotalLosses() }}
        </p>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 border-yellow-500">
        <p class="text-xs text-gray-600 dark:text-gray-400 uppercase">Total N</p>
        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
          {{ calculateTotalDraws() }}
        </p>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 border-l-4 border-green-500">
        <p class="text-xs text-gray-600 dark:text-gray-400 uppercase">Points</p>
        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
          {{ calculateTotalPoints() }}
        </p>
      </div>
    </div>

    <!-- Timeline responsive -->
    <div class="space-y-4">
      <div
        v-for="(stat, index) in stats"
        :key="stat.id"
        class="bg-white dark:bg-gray-900 rounded-lg shadow overflow-hidden"
      >
        <!-- Timeline marker -->
        <div v-if="index !== 0" class="h-2 bg-gradient-to-r from-blue-200 via-purple-200 to-pink-200 dark:from-blue-800 dark:via-purple-800 dark:to-pink-800"></div>

        <div class="p-4 md:p-6">
          <!-- Mois et date -->
          <div class="flex justify-between items-start mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div>
              <p class="text-xs text-gray-500 dark:text-gray-400">Mois</p>
              <p class="text-lg md:text-xl font-bold text-gray-900 dark:text-white">
                {{ formatMonth(stat.month) }}
              </p>
            </div>
            <Link
              :href="`/admin/team-stats/${stat.id}/edit`"
              class="px-3 py-1 text-xs font-medium text-blue-600 dark:text-blue-400
                     hover:bg-blue-50 dark:hover:bg-blue-900 rounded transition"
            >
              ✏️ Éditer
            </Link>
          </div>

          <!-- Résultats - Version responsive -->
          <div class="grid grid-cols-3 md:grid-cols-6 gap-2 md:gap-3">
            <!-- Victoires -->
            <div class="col-span-1 text-center p-3 bg-green-50 dark:bg-green-900 rounded-lg border-2 border-green-200 dark:border-green-700">
              <p class="text-xs text-green-700 dark:text-green-300 font-semibold mb-1">V</p>
              <p class="text-2xl md:text-3xl font-bold text-green-900 dark:text-green-100">
                {{ stat.wins }}
              </p>
              <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                {{ stat.wins * 3 }}pts
              </p>
            </div>

            <!-- Défaites -->
            <div class="col-span-1 text-center p-3 bg-red-50 dark:bg-red-900 rounded-lg border-2 border-red-200 dark:border-red-700">
              <p class="text-xs text-red-700 dark:text-red-300 font-semibold mb-1">D</p>
              <p class="text-2xl md:text-3xl font-bold text-red-900 dark:text-red-100">
                {{ stat.losses }}
              </p>
              <p class="text-xs text-red-600 dark:text-red-400 mt-1">0pts</p>
            </div>

            <!-- Nuls -->
            <div class="col-span-1 text-center p-3 bg-yellow-50 dark:bg-yellow-900 rounded-lg border-2 border-yellow-200 dark:border-yellow-700">
              <p class="text-xs text-yellow-700 dark:text-yellow-300 font-semibold mb-1">N</p>
              <p class="text-2xl md:text-3xl font-bold text-yellow-900 dark:text-yellow-100">
                {{ stat.draws }}
              </p>
              <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                {{ stat.draws }}pt{{ stat.draws > 1 ? 's' : '' }}
              </p>
            </div>

            <!-- Total matchs -->
            <div class="col-span-1 text-center p-3 bg-blue-50 dark:bg-blue-900 rounded-lg border-2 border-blue-200 dark:border-blue-700">
              <p class="text-xs text-blue-700 dark:text-blue-300 font-semibold mb-1">Matchs</p>
              <p class="text-2xl md:text-3xl font-bold text-blue-900 dark:text-blue-100">
                {{ stat.wins + stat.losses + stat.draws }}
              </p>
            </div>

            <!-- Points totaux -->
            <div class="col-span-1 text-center p-3 bg-purple-50 dark:bg-purple-900 rounded-lg border-2 border-purple-200 dark:border-purple-700">
              <p class="text-xs text-purple-700 dark:text-purple-300 font-semibold mb-1">Points</p>
              <p class="text-2xl md:text-3xl font-bold text-purple-900 dark:text-purple-100">
                {{ (stat.wins * 3) + stat.draws }}
              </p>
            </div>

            <!-- Taux victoire -->
            <div class="col-span-1 text-center p-3 bg-indigo-50 dark:bg-indigo-900 rounded-lg border-2 border-indigo-200 dark:border-indigo-700">
              <p class="text-xs text-indigo-700 dark:text-indigo-300 font-semibold mb-1">Taux V.</p>
              <p class="text-2xl md:text-3xl font-bold text-indigo-900 dark:text-indigo-100">
                {{ ((stat.wins / (stat.wins + stat.losses + stat.draws)) * 100).toFixed(0) }}%
              </p>
            </div>
          </div>

          <!-- Statistiques visuelles -->
          <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <!-- Barre de progression -->
            <div class="mb-4">
              <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold mb-2">Distribution</p>
              <div class="flex h-8 rounded-lg overflow-hidden shadow">
                <div
                  :style="{ width: `${(stat.wins / (stat.wins + stat.losses + stat.draws)) * 100}%` }"
                  class="bg-green-500 flex items-center justify-center text-white text-xs font-bold"
                >
                  <span v-if="(stat.wins / (stat.wins + stat.losses + stat.draws)) * 100 > 15">
                    {{ stat.wins }}
                  </span>
                </div>
                <div
                  :style="{ width: `${(stat.losses / (stat.wins + stat.losses + stat.draws)) * 100}%` }"
                  class="bg-red-500 flex items-center justify-center text-white text-xs font-bold"
                >
                  <span v-if="(stat.losses / (stat.wins + stat.losses + stat.draws)) * 100 > 15">
                    {{ stat.losses }}
                  </span>
                </div>
                <div
                  :style="{ width: `${(stat.draws / (stat.wins + stat.losses + stat.draws)) * 100}%` }"
                  class="bg-yellow-500 flex items-center justify-center text-white text-xs font-bold"
                >
                  <span v-if="(stat.draws / (stat.wins + stat.losses + stat.draws)) * 100 > 15">
                    {{ stat.draws }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Moyenne points par match -->
            <div class="grid grid-cols-2 gap-2">
              <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded">
                <p class="text-xs text-gray-600 dark:text-gray-400">Points/Match</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">
                  {{ (((stat.wins * 3) + stat.draws) / (stat.wins + stat.losses + stat.draws)).toFixed(2) }}
                </p>
              </div>
              <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded">
                <p class="text-xs text-gray-600 dark:text-gray-400">Efficacité</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">
                  {{ (((stat.wins * 3) + stat.draws) / ((stat.wins + stat.losses + stat.draws) * 3) * 100).toFixed(1) }}%
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Message vide -->
      <div v-if="stats.length === 0" class="text-center py-12 bg-white dark:bg-gray-900 rounded-lg shadow">
        <p class="text-gray-600 dark:text-gray-400 mb-4">
          Aucune stat enregistrée pour cette équipe
        </p>
        <Link
          href="/admin/team-stats/create"
          class="inline-block px-4 py-2 text-sm font-medium text-white
                 bg-green-600 hover:bg-green-700 rounded-lg transition"
        >
          ➕ Ajouter une stat
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  team: Object,
  stats: Array,
});

const formatMonth = (date) => {
  return new Intl.DateTimeFormat('fr-FR', {
    month: 'long',
    year: 'numeric',
  }).format(new Date(date));
};

const calculateTotalWins = () => {
  return props.stats.reduce((sum, stat) => sum + stat.wins, 0);
};

const calculateTotalLosses = () => {
  return props.stats.reduce((sum, stat) => sum + stat.losses, 0);
};

const calculateTotalDraws = () => {
  return props.stats.reduce((sum, stat) => sum + stat.draws, 0);
};

const calculateTotalPoints = () => {
  return props.stats.reduce((sum, stat) => sum + (stat.wins * 3) + stat.draws, 0);
};
</script>





