<?php

use Inertia\Inertia;
use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TeamStatController;
use App\Http\Controllers\Bureau\BureauMemberController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Bureau\BureauStatController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\GalleryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PlayerOfTheMonthController;
use App\Http\Controllers\Auth\GoogleAuthController;

// Routes d'authentification Google
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// Route::get('/', function () {
//     if (auth()->check()) {
//         return redirect()->route('dashboard');
//     }
    
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'is.active'])
    ->name('dashboard');



Route::middleware(['auth', 'is.active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::post('/profile/poster', [ProfileController::class, 'updatePoster'])->name('profile.poster.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Members management routes - Admin only
    Route::middleware(middleware: 'role:admin')->group(function () {
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('members.store');
        Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
        Route::patch('/members/{member}', [MemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
        Route::patch('/members/{member}/toggle-status', [MemberController::class, 'toggleStatus'])->name('members.toggle-status');
        Route::patch('/members/{member}/role', [MemberController::class, 'updateRole'])->name('members.update-role');
    });

    Route::prefix('bureau/members')->middleware('role:bureau')->group(function () {
        Route::get('/', [BureauMemberController::class, 'index'])->name('bureau.members.index');
    });
});

Route::prefix('reflections')->group(function () {
    Route::get('/', [ReflectionController::class, 'index'])->name('reflections.index');
    Route::get('/{reflection}', [ReflectionController::class, 'show'])->name('reflections.show');
    Route::get('/create', [ReflectionController::class, 'create'])->name('admin.reflections.create');
    Route::post('/', [ReflectionController::class, 'store'])->name('reflections.store');
    Route::get('/{id}/edit', [ReflectionController::class, 'edit'])->name('admin.reflections.edit');
    Route::get('/{id}/validate', [ReflectionController::class, 'validate'])->name('reflections.validate');
    Route::put('/{id}', [ReflectionController::class, 'update'])->name('admin.reflections.update');
    Route::delete('/{id}', [ReflectionController::class, 'destroy'])->name('reflections.destroy');
    Route::patch('/{id}/toggle', [ReflectionController::class, 'toggle'])->name('admin.reflections.toggle'); // activation/desactivationRoute::post('/{id}/validate', [ReflectionController::class, 'validateAfterDelay'])->name('admin.reflections.validate');
});

/**
 * 🟢 Stats publiques (consultation libre)
 */

Route::get('/stats', [StatController::class, 'index'])
    ->name('stats.index');

// Route::middleware(['auth', 'role:admin']) // 👉 Accès réservé aux Admins
//     ->prefix('admin')                    // 👉 URL commence par /admin
//     ->name('admin.')                     // 👉 Nom des routes commence par admin.
 


Route::get('/admin', [AdminController::class,'index'])->name('Admin.AdminLayout');


Route::get('/admin/create', [StatController::class,'create'])->name('Admin.CreateStats');
/**
 * 📊 Stats admin (accès authentifié)
*/
Route::get('/admin/stats', [StatController::class, 'index'])
->middleware('auth')
    ->name('admin.stats.index');

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
Route::middleware(['auth', 'is.active'])
    ->prefix('admin')
    ->name('admin.')
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

        // Routes admin avec authentification
        Route::middleware(['auth'])
            ->prefix('admin')
            ->name('admin.')
            ->group(function () {

                Route::get('/stats', [StatController::class, 'index'])
                    ->name('stats.index');

                Route::get('/stats/classements', [StatController::class, 'adminClassementsIndex'])
                    ->name('stats.classements');


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
        Route::get('/teams', [TeamController::class, 'vue'])->name('admin.teams');
        Route::get('/teams/index', [TeamController::class, 'index'])->name('admin.teams.index');
        Route::get('teams/create', [TeamController::class, 'create'])->name('admin.teams.create');
        Route::post('/teams', [TeamController::class, 'store'])->name('admin.teams.store');
        Route::get('/teams/{id}/edit', [TeamController::class, 'edit'])->name('admin.teams.edit');
        Route::put('/teams/{team}', [TeamController::class, 'update'])->name('admin.teams.update');
        Route::delete('/teams/{id}', [TeamController::class, 'destroy'])->name('admin.teams.destroy');
        Route::get('/teams/{team}/affect', [TeamController::class, 'affectPage'])
            ->name('teams.affect');
        Route::post('/teams/{team}/affect/save', [TeamController::class, 'saveAffect']);

    });



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

    Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');


    Route::get('/gallery-upload', function () {
        return Inertia::render('GalleryUpload');
    })->name('gallery.upload');


    // Déclare un ensemble de routes RESTful pour le controller GalleryController
    // On limite volontairement aux méthodes utiles :
    // - store   : pour uploader une nouvelle photo
    // - update  : pour modifier la description d'une photo existante
    // - destroy : pour supprimer une photo
    Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
    Route::put('/galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
    Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');



    Route::post('/galleries/{gallery}/like', [GalleryController::class, 'like'])->name('galleries.like');
    Route::delete('/galleries/{gallery}/unlike', [GalleryController::class, 'unlike'])->name('galleries.unlike');
});

Route::prefix('admin/news')->group(function () {

    Route::get('/index', [NewsController::class, 'index'])->name('admin.news.index');

    Route::get('/showReglement', [NewsController::class, 'showReglement'])->name('admin.news.showReglement');

    Route::get('/bannerplayermonth', [PlayerOfTheMonthController::class, 'index'])
        ->name('admin.news.bannerplayermonth');

    Route::put('/BannerPlayerMonth', [PlayerOfTheMonthController::class, 'update']);

    Route::delete('/BannerPlayerMonth', [PlayerOfTheMonthController::class, 'destroy']);

    Route::get('/create', [NewsController::class, 'create'])->name('admin.news.create');

    Route::post('/', [NewsController::class, 'store'])->name('admin.news.store');

    Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('admin.news.edit');

    Route::put('/{id}', [NewsController::class, 'update'])->name('admin.news.update');

    Route::delete('/{id}', [NewsController::class, 'destroy'])->name('admin.news.destroy');

    Route::patch('/{id}/toggle-banner', [NewsController::class, 'toggleBanner'])->name('admin.news.toggle_banner'); // joueur du mois
});

require __DIR__ . '/auth.php';
