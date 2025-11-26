<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\GalleryController;
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


/*
|--------------------------------------------------------------------------
| Routes protégées pour la galerie
|--------------------------------------------------------------------------
|
| Ces routes permettent aux utilisateurs authentifiés (admin ou membres)
| d'interagir avec la galerie. Comme il s'agit d'une galerie simple,
| seules les actions nécessaires (store, update, destroy) sont exposées.
|
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/gallery-upload', function () {
        return Inertia::render('GalleryUpload');
    })->name('gallery.upload');


    // Déclare un ensemble de routes RESTful pour le controller GalleryController
    // On limite volontairement aux méthodes utiles :
    // - store   : pour uploader une nouvelle photo
    // - update  : pour modifier la description d'une photo existante
    // - destroy : pour supprimer une photo
    Route::resource('galleries', GalleryController::class)
        ->only(['store', 'update', 'destroy']);
});
