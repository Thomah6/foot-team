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



       Route::get('/stats', [StatController::class, 'index'])
            ->name('stats.index');

Route::middleware(['auth', 'role:admin']) // 👉 Accès réservé aux Admins
    ->prefix('admin')                    // 👉 URL commence par /admin
    ->name('admin.')                     // 👉 Nom des routes commence par admin.
    ->group(function () {

        /**
         * 🟦 1. Page principale Stats Admin
         * Liste générale / accès aux sous-pages : validation, classement, ajout
         */
 


        /**
         * 🟦 2. Ajouter une statistique (SAISIE MANUELLE)
         * Ex : ajouter buts / passes après un match
         * Validation par un admin ensuite.
         */
        Route::post('/stats', [StatController::class, 'store'])
            ->name('stats.store');


        /**
         * 🟦 3. Lister les stats en attente de validation
         * Permet à l'admin de valider ou rejeter
         */
        Route::get('/stats/pending', [StatController::class, 'pending'])
            ->name('stats.pending');


        /**
         * 🟦 4. Valider une stat
         * /admin/stats/12/validate → valide la stat ID=12
         */
        Route::post('/stats/{stat}/validate', [StatController::class, 'validateStat'])
            ->name('stats.validate');


        /**
         * 🟦 5. Classement des buteurs
         * Filtré uniquement sur les stats validées
         * Seuil : min 2 buts
         */
        Route::get('/stats/classements/buteurs', [StatController::class, 'classementsGoals'])
            ->name('stats.classements.buteurs');


        /**
         * 🟦 6. Classement des passeurs
         * Filtré sur les assists validées
         */
        Route::get('/stats/classements/passeurs', [StatController::class, 'classementsAssists'])
            ->name('stats.classements.passeurs');


        /**
         * 🟦 7. Classement des gardiens
         * Basé sur “goals_against”, classement inversé (moins encaisse → meilleur)
         */
        Route::get('/stats/classements/gardiens', [StatController::class, 'classementsGardiens'])
            ->name('stats.classements.gardiens');
    });


require __DIR__.'/auth.php';
