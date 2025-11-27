<?php

use App\Http\Controllers\Bureau\BureauMemberController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\Admin\MemberController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Members management routes - Admin only
    Route::middleware('role:admin')->group(function () {
        
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('members.store');
        Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
        Route::patch('/members/{member}', [MemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
        Route::patch('/members/{member}/toggle-status', [MemberController::class, 'toggleStatus'])->name('members.toggle-status');
        Route::patch('/members/{member}/role', [MemberController::class, 'updateRole'])->name('members.update-role');
    });

    Route::prefix('bureau/members')->middleware('role:bureau')->group(function(){
        Route::get('/',[BureauMemberController::class,'index'])->name('bureau.members.index');
    });
});

Route::prefix('reflections')->group(function () {
    Route::get('/', [ReflectionController::class, 'index'])->name('reflections.index');
    Route::get('/{reflection}', [ReflectionController::class, 'show'])->name('reflections.show');
    Route::get('/create', [ReflectionController::class, 'create'])->name('admin.reflections.create');
    Route::post('/', [ReflectionController::class, 'store'])->name('admin.reflections.store');
    Route::get('/{id}/edit', [ReflectionController::class, 'edit'])->name('admin.reflections.edit');
    Route::get('/{id}/validate', [ReflectionController::class, 'validate'])->name('reflections.validate');
    Route::put('/{id}', [ReflectionController::class, 'update'])->name('admin.reflections.update');
    Route::delete('/{id}', [ReflectionController::class, 'destroy'])->name('reflections.destroy');
    Route::patch('/{id}/toggle', [ReflectionController::class, 'toggle'])->name('admin.reflections.toggle'); // activation/desactivationRoute::post('/{id}/validate', [ReflectionController::class, 'validateAfterDelay'])->name('admin.reflections.validate');
});

/**
 * 🟢 Stats publiques (consultation libre)
 */
Route::get('/stats', [StatController::class, 'publicIndex'])->name('stats.public.index');

/**
 * 🏆 Joueur du Mois (public)
 */
Route::get('/joueur-du-mois', [StatController::class, 'currentPlayerOfMonth'])
    ->name('player.month.current');

Route::get('/joueur-du-mois/historique', [StatController::class, 'historyPlayerOfMonth'])
    ->name('player.month.history');

Route::get('/joueur-du-mois/{month}/stats', [StatController::class, 'monthlyStats'])
    ->name('player.month.stats');

/**
 * 📊 Classements publics
 */
Route::get('/classements', [StatController::class, 'classementsIndex'])
    ->name('stats.classements.index');

Route::get('/classements/general', [StatController::class, 'classementGeneral'])
    ->name('stats.classement.general');

Route::get('/classements/buteurs', [StatController::class, 'classementsGoals'])
    ->name('stats.classement.buteurs');

Route::get('/classements/passeurs', [StatController::class, 'classementsAssists'])
    ->name('stats.classement.passeurs');

Route::get('/classements/gardiens', [StatController::class, 'classementsGardiens'])
    ->name('stats.classement.gardiens');

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
    });


//Routes pour la creation de team et mercato par l'admin
// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::resource('teams', TeamController::class);

//     Route::post('/teams/assign-members', [TeamController::class, 'assignMembers']);
//     Route::post('/teams/mercato', [TeamController::class, 'mercato']);
// });
Route::get('/teams', [TeamController::class, 'vue'])->name('admin.teams');
Route::get('/teams/index', [TeamController::class, 'index'])->name('admin.teams.index');
Route::get('teams/create', [TeamController::class, 'create'])->name('admin.teams.create');
Route::post('/teams', [TeamController::class, 'store'])->name('admin.teams.store');
Route::get('/teams/{id}/edit', [TeamController::class, 'edit'])->name('admin.teams.edit');
Route::put('/teams/{team}', [TeamController::class, 'update'])->name('admin.teams.update');
Route::delete('/teams/{id}', [TeamController::class, 'destroy'])->name('admin.teams.destroy');
Route::get('/teams/{team}/affect', [TeamController::class, 'affectPage'])
    ->name('teams.affect');
Route::post('/teams/{team}/affect/save', [TeamController::class, 'saveAffect'])->name('teams.affect.save');
Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');


require __DIR__ . '/auth.php';
