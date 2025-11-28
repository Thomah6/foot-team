<?php

use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\Admin\TeamStatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\Bureau\BureauMemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PlayerOfTheMonthController;
use App\Http\Controllers\GalleryController;
use Inertia\Inertia;

// Routes d'authentification Google
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// Redirection racine
Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Routes publiques
Route::get('/stats', [StatController::class, 'publicIndex'])->name('stats.public.index');
Route::get('/joueur-du-mois', [StatController::class, 'currentPlayerOfMonth'])->name('player.month.current');
Route::get('/joueur-du-mois/historique', [StatController::class, 'historyPlayerOfMonth'])->name('player.month.history');
Route::get('/joueur-du-mois/{month}/stats', [StatController::class, 'monthlyStats'])->name('player.month.stats');
Route::get('/classements', [StatController::class, 'classementsIndex'])->name('stats.classements.index');
Route::get('/classements/general', [StatController::class, 'classementGeneral'])->name('stats.classement.general');
Route::get('/classements/buteurs', [StatController::class, 'classementsGoals'])->name('stats.classement.buteurs');
Route::get('/classements/passeurs', [StatController::class, 'classementsAssists'])->name('stats.classement.passeurs');
Route::get('/classements/gardiens', [StatController::class, 'classementsGardiens'])->name('stats.classement.gardiens');

// Routes authentifiées
Route::middleware(['auth', 'is.active'])->group(function () {
    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::post('/profile/poster', [ProfileController::class, 'updatePoster'])->name('profile.poster.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des membres (Admin uniquement)
    Route::middleware('role:admin')->group(function () {
        Route::resource('members', MemberController::class)->except(['show']);
        Route::patch('/members/{member}/toggle-status', [MemberController::class, 'toggleStatus'])->name('members.toggle-status');
        Route::patch('/members/{member}/role', [MemberController::class, 'updateRole'])->name('members.update-role');
    });

    // Espace bureau
    Route::prefix('bureau')->middleware('role:bureau')->group(function () {
        Route::get('/members', [BureauMemberController::class, 'index'])->name('bureau.members.index');
    });

    // Réflexions
    Route::prefix('reflections')->group(function () {
        Route::get('/', [ReflectionController::class, 'index'])->name('reflections.index');
        Route::get('/create', [ReflectionController::class, 'create'])->name('reflections.create');
        Route::post('/', [ReflectionController::class, 'store'])->name('reflections.store');
        Route::get('/{reflection}', [ReflectionController::class, 'show'])->name('reflections.show');
        Route::get('/{reflection}/edit', [ReflectionController::class, 'edit'])->name('reflections.edit');
        Route::put('/{reflection}', [ReflectionController::class, 'update'])->name('reflections.update');
        Route::delete('/{reflection}', [ReflectionController::class, 'destroy'])->name('reflections.destroy');
        Route::patch('/{reflection}/toggle', [ReflectionController::class, 'toggle'])->name('reflections.toggle');
        Route::post('/{reflection}/validate', [ReflectionController::class, 'validateReflection'])->name('reflections.validate');
    });

    // Administration
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        
        // Gestion des statistiques
        Route::get('/stats', [StatController::class, 'index'])->name('stats.index');
        Route::get('/stats/create', [StatController::class, 'create'])->name('stats.create');
        Route::post('/stats', [StatController::class, 'store'])->name('stats.store');
        Route::get('/stats/pending', [StatController::class, 'pending'])->name('stats.pending');
        Route::post('/stats/{stat}/validate', [StatController::class, 'validateStat'])->name('stats.validate');
        Route::delete('/stats/{stat}/reject', [StatController::class, 'rejectStat'])->name('stats.reject');
        Route::get('/stats/classements', [StatController::class, 'adminClassementsIndex'])->name('stats.classements');
        
        // Gestion des équipes
        Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
        Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
        Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
        Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
        Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
        Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
        Route::get('/teams/{team}/affect', [TeamController::class, 'affectPage'])->name('teams.affect');
        Route::post('/teams/{team}/affect/save', [TeamController::class, 'saveAffect'])->name('teams.affect.save');
        Route::post('/teams/assign-members', [TeamController::class, 'assignMembers'])->name('teams.assign-members');
        Route::post('/teams/mercato', [TeamController::class, 'mercato'])->name('teams.mercato');
    });
});
// Routes pour la galerie
Route::middleware(['auth'])->group(function () {
    // Affichage de la galerie et du formulaire d'upload
    Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');
    Route::get('/gallery-upload', function () {
        return Inertia::render('GalleryUpload');
    })->name('gallery.upload');

    // Gestion des photos
    Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
    Route::put('/galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
    Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
    
    // Gestion des likes
    Route::post('/galleries/{gallery}/like', [GalleryController::class, 'like'])->name('galleries.like');
    Route::delete('/galleries/{gallery}/unlike', [GalleryController::class, 'unlike'])->name('galleries.unlike');
});

// Routes d'administration des actualités
Route::prefix('admin/news')->name('admin.news.')->middleware(['auth', 'role:admin'])->group(function () {
    // Gestion des actualités
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/create', [NewsController::class, 'create'])->name('create');
    Route::post('/', [NewsController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('edit');
    Route::put('/{id}', [NewsController::class, 'update'])->name('update');
    Route::delete('/{id}', [NewsController::class, 'destroy'])->name('destroy');
    Route::patch('/{id}/toggle-banner', [NewsController::class, 'toggleBanner'])->name('toggle_banner');
    
    // Règlement
    Route::get('/showReglement', [NewsController::class, 'showReglement'])->name('showReglement');
    
    // Joueur du mois
    Route::prefix('bannerplayermonth')->group(function () {
        Route::get('/', [PlayerOfTheMonthController::class, 'index'])->name('bannerplayermonth');
        Route::put('/', [PlayerOfTheMonthController::class, 'update']);
        Route::delete('/', [PlayerOfTheMonthController::class, 'destroy']);
    });
});

// Routes d'authentification




// Routes de Finances 
Route::get('/finances', [FinanceController::class, 'index'])->name('finances.index');
Route::get('/finances/depot/create', [FinanceController::class, 'createDepot'])->name('finances.createDepot');
Route::post('/finances', [FinanceController::class, 'storeDepot'])->name('finances.storeDepot');
Route::get('/finances/depense/create', [FinanceController::class, 'createDepense'])->name('finances.createDepense');
Route::post('/finances/depense', [FinanceController::class, 'storeDepense'])->name('finances.storeDepense');
Route::post('/finances/valider/{id}', [FinanceController::class, 'valider'])->name('finances.valider');
Route::post('/finances/valider-tous', [FinanceController::class, 'validerTous'])->name('finances.validerTous');