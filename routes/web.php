<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StatController;

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
});

/**
 * 🟦 Public routes (accessible sans authentification)
 */

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


require __DIR__.'/auth.php';
