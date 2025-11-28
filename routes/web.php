<?php

use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\Admin\TeamStatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Bureau\BureauMemberController;
use App\Http\Controllers\Bureau\BureauStatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PlayerOfTheMonthController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\SuggestionController;
// use App\Http\Controllers\RegulationControler;
use App\Http\Controllers\CommentsSuggestionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Routes d'authentification Google
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// Page d'accueil

// Redirection racine si authentifié
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

// Routes de vote
Route::middleware('auth')->group(function () {
    Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
    Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
    Route::post('/vote/validate', [VoteController::class, 'validateVote'])->name('vote.validate');
});

// Route dashboard avec middleware
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'is.active'])
    ->name('dashboard');

// Routes publiques
Route::get('/stats', [StatController::class, 'publicIndex'])->name('stats.public.index');

// Routes du joueur du mois
Route::get('/joueur-du-mois', [StatController::class, 'currentPlayerOfMonth'])->name('player.month.current');
Route::get('/joueur-du-mois/historique', [StatController::class, 'historyPlayerOfMonth'])->name('player.month.history');
Route::get('/joueur-du-mois/{month}/stats', [StatController::class, 'monthlyStats'])->name('player.month.stats');

// Routes des classements
Route::get('/classements', [StatController::class, 'classementsIndex'])->name('stats.classements.index');
Route::get('/classements/general', [StatController::class, 'classementGeneral'])->name('stats.classement.general');
Route::get('/classements/buteurs', [StatController::class, 'classementsGoals'])->name('stats.classement.buteurs');
Route::get('/classements/passeurs', [StatController::class, 'classementsAssists'])->name('stats.classement.passeurs');
Route::get('/classements/gardiens', [StatController::class, 'classementsGardiens'])->name('stats.classement.gardiens');

// Routes pour les actualités (news)
Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news.index');
    Route::get('/reglement', [NewsController::class, 'showReglement'])->name('news.reglement');
});

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
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('members.store');
        Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
        Route::patch('/members/{member}', [MemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
        Route::patch('/members/{member}/toggle-status', [MemberController::class, 'toggleStatus'])->name('members.toggle-status');
        Route::patch('/members/{member}/role', [MemberController::class, 'updateRole'])->name('members.update-role');
    });

    // Gestion des présences
    // Route::get('/presence', [PresenceController::class, 'index'])->name('presence.index');
    // Route::get('/presence/history', [PresenceController::class, 'history'])->name('presence.history');
    // Route::post('/presence', [PresenceController::class, 'store'])->name('presence.store');
    // Route::get('/presence/day', [PresenceController::class, 'getByDate'])->name('presence.getByDate');

    // // Gestion des présences (Admin)
    // Route::middleware('role:admin')->group(function () {
    //     Route::patch('/presence/{presence}/validate', [PresenceController::class, 'validate'])->name('presence.validate');
    //     Route::patch('/presence/{presence}', [PresenceController::class, 'update'])->name('presence.update');
    //     Route::get('/presence/monthly-report', [PresenceController::class, 'monthlyReport'])->name('presence.monthlyReport');
    // });

    // Espace bureau
    Route::prefix('bureau')->middleware('role:bureau')->group(function () {
        Route::get('/members', [BureauMemberController::class, 'index'])->name('bureau.members.index');
        
        // Statistiques du bureau
        Route::prefix('stats')->group(function () {
            Route::get('/', [BureauStatController::class, 'index'])->name('bureau.stats.index');
            Route::get('/leaderboards', [BureauStatController::class, 'leaderboards'])->name('bureau.stats.leaderboards');
            Route::get('/leaderboards/goals', [BureauStatController::class, 'goalLeaders'])->name('bureau.stats.leaderboards.goals');
            Route::get('/leaderboards/assists', [BureauStatController::class, 'assistLeaders'])->name('bureau.stats.leaderboards.assists');
            Route::get('/leaderboards/goalkeepers', [BureauStatController::class, 'goalkeeperLeaders'])->name('bureau.stats.leaderboards.goalkeepers');
            Route::get('/members/{user}/stats', [BureauStatController::class, 'memberStats'])->name('bureau.stats.member');
        });
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
        
        // Routes spécifiques pour l'administration des réflexions
        Route::middleware('role:admin')->group(function () {
            Route::get('/{id}/validate', [ReflectionController::class, 'validateReflection'])->name('admin.reflections.validate');
        });
    });

    // Administration
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        
        // Gestion des statistiques
        Route::prefix('stats')->group(function () {
            Route::get('/', [StatController::class, 'index'])->name('stats.index');
            Route::get('/create', [StatController::class, 'create'])->name('stats.create');
            Route::post('/', [StatController::class, 'store'])->name('stats.store');
            Route::get('/pending', [StatController::class, 'pending'])->name('stats.pending');
            Route::post('/{stat}/validate', [StatController::class, 'validateStat'])->name('stats.validate');
            Route::delete('/{stat}/reject', [StatController::class, 'rejectStat'])->name('stats.reject');
            
            // Statistiques d'équipe
            Route::prefix('team')->name('team.')->group(function () {
                Route::get('/', [TeamStatController::class, 'index'])->name('index');
                Route::get('/create', [TeamStatController::class, 'create'])->name('create');
                Route::post('/', [TeamStatController::class, 'store'])->name('store');
                Route::get('/{teamStat}/edit', [TeamStatController::class, 'edit'])->name('edit');
                Route::patch('/{teamStat}', [TeamStatController::class, 'update'])->name('update');
                Route::delete('/{teamStat}', [TeamStatController::class, 'destroy'])->name('destroy');
                Route::get('/team/{team}', [TeamStatController::class, 'byTeam'])->name('by-team');
                Route::post('/bulk-validate', [TeamStatController::class, 'bulkValidate'])->name('bulk-validate');
            });
        });
        
        // Classements
        Route::prefix('stats/classements')->name('stats.classements.')->group(function () {
            Route::get('/', [StatController::class, 'adminClassementsIndex'])->name('index');
            Route::get('/buteurs', [StatController::class, 'classementsGoals'])->name('buteurs');
            Route::get('/passeurs', [StatController::class, 'classementsAssists'])->name('passeurs');
            Route::get('/gardiens', [StatController::class, 'classementsGardiens'])->name('gardiens');
        });
        
        // Gestion des équipes
        Route::prefix('teams')->name('teams.')->group(function () {
            Route::get('/', [TeamController::class, 'index'])->name('index');
            Route::get('/create', [TeamController::class, 'create'])->name('create');
            Route::post('/', [TeamController::class, 'store'])->name('store');
            Route::get('/{team}/edit', [TeamController::class, 'edit'])->name('edit');
            Route::put('/{team}', [TeamController::class, 'update'])->name('update');
            Route::delete('/{team}', [TeamController::class, 'destroy'])->name('destroy');
            Route::get('/{team}/affect', [TeamController::class, 'affectPage'])->name('affect');
            Route::post('/{team}/affect/save', [TeamController::class, 'saveAffect'])->name('affect.save');
            
            // Mercato
            Route::post('/assign-members', [TeamController::class, 'assignMembers'])->name('assign-members');
            Route::post('/mercato', [TeamController::class, 'mercato'])->name('mercato');
        });
        
        // Gestion des actualités (Admin)
        Route::prefix('news')->name('news.')->group(function () {
            Route::get('/', [NewsController::class, 'index'])->name('index');
            Route::get('/create', [NewsController::class, 'create'])->name('create');
            Route::post('/', [NewsController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('edit');
            Route::put('/{id}', [NewsController::class, 'update'])->name('update');
            Route::delete('/{id}', [NewsController::class, 'destroy'])->name('destroy');
            Route::get('/showReglement', [NewsController::class, 'showReglement'])->name('showReglement');
            Route::patch('/{id}/toggle-banner', [NewsController::class, 'toggleBanner'])->name('toggle-banner');
            
            // Bannière du joueur du mois
            Route::prefix('bannerplayermonth')->name('bannerplayermonth.')->group(function () {
                Route::get('/', [PlayerOfTheMonthController::class, 'index'])->name('index');
                Route::put('/', [PlayerOfTheMonthController::class, 'update'])->name('update');
                Route::delete('/', [PlayerOfTheMonthController::class, 'destroy'])->name('destroy');
            });
        });    
        
        // Gestion des statistiques d'équipe
        Route::prefix('team-stats')->name('team-stats.')->group(function () {
            Route::get('/', [TeamStatController::class, 'index'])->name('index');
            Route::get('/create', [TeamStatController::class, 'create'])->name('create');
            Route::post('/', [TeamStatController::class, 'store'])->name('store');
            Route::get('/{teamStat}/edit', [TeamStatController::class, 'edit'])->name('edit');
            Route::patch('/{teamStat}', [TeamStatController::class, 'update'])->name('update');
            Route::delete('/{teamStat}', [TeamStatController::class, 'destroy'])->name('destroy');
            Route::get('/team/{team}', [TeamStatController::class, 'byTeam'])->name('by-team');
            Route::get('/api/current-month', [TeamStatController::class, 'currentMonthStats'])->name('current-month-api');
            Route::post('/bulk-validate', [TeamStatController::class, 'bulkValidate'])->name('bulk-validate');
        });
        // Les routes d'administration des actualités sont déjà définies plus haut
        });
    });

    // Espace bureau - Statistiques
    Route::prefix('bureau/stats')->middleware('role:bureau')->group(function () {
        Route::get('/', [BureauStatController::class, 'index'])->name('bureau.stats.index');
        Route::get('/leaderboards', [BureauStatController::class, 'leaderboards'])->name('bureau.stats.leaderboards');
        Route::get('/leaderboards/goals', [BureauStatController::class, 'goalLeaders'])->name('bureau.stats.leaderboards.goals');
        Route::get('/leaderboards/assists', [BureauStatController::class, 'assistLeaders'])->name('bureau.stats.leaderboards.assists');
        Route::get('/leaderboards/goalkeepers', [BureauStatController::class, 'goalkeeperLeaders'])->name('bureau.stats.leaderboards.goalkeepers');
        Route::get('/members/{user}/stats', [BureauStatController::class, 'memberStats'])->name('bureau.stats.member');
    });

    // Galerie
    Route::prefix('galleries')->group(function () {
        Route::get('/', [GalleryController::class, 'index'])->name('galleries.index');
        Route::get('/gallery-upload', function () {
            return Inertia::render('GalleryUpload');
        })->name('gallery.upload');
        Route::post('/', [GalleryController::class, 'store'])->name('galleries.store');
        Route::put('/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
        Route::delete('/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
        Route::post('/{gallery}/like', [GalleryController::class, 'like'])->name('galleries.like');
        Route::delete('/{gallery}/unlike', [GalleryController::class, 'unlike'])->name('galleries.unlike');
    });

    // Finances
    Route::prefix('finances')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('finances.index');
        Route::get('/depot/create', [FinanceController::class, 'createDepot'])->name('finances.createDepot');
        Route::post('/', [FinanceController::class, 'storeDepot'])->name('finances.storeDepot');
        Route::get('/depense/create', [FinanceController::class, 'createDepense'])->name('finances.createDepense');
        Route::post('/depense', [FinanceController::class, 'storeDepense'])->name('finances.storeDepense');
        Route::post('/valider/{id}', [FinanceController::class, 'valider'])->name('finances.valider');
        Route::post('/valider-tous', [FinanceController::class, 'validerTous'])->name('finances.validerTous');
    });
});

// Routes pour les règlements
// Route::middleware(['auth', 'is.active'])->group(function () {
//     Route::resource('regulations', RegulationControler::class);
//     Route::post('/regulations/content', [RegulationControler::class, 'storeContent'])->name('regulations.storeContent');
// });

// Routes pour les ajustements financiers
Route::middleware(['auth', 'is.active'])->group(function () {
    Route::get('/finances/ajustement', [FinanceController::class, 'createAjustement'])->name('finances.createAjustement');
    Route::post('/finances/ajustement', [FinanceController::class, 'storeAjustement'])->name('finances.storeAjustement');
});

// Routes pour les suggestions
Route::middleware(['auth', 'is.active'])->group(function () {
    // Suggestions
    Route::prefix('suggestions')->group(function () {
        Route::get('/', [SuggestionController::class, 'index'])->name('suggestions.index');
        Route::post('/', [SuggestionController::class, 'store'])->name('suggestions.store');
        Route::post('/{suggestion}/react', [SuggestionController::class, 'react'])->name('suggestions.react');
        Route::delete('/{suggestion}', [SuggestionController::class, 'destroy'])->name('suggestions.destroy');
        
        // Commentaires sur les suggestions
        Route::post('/{suggestion}/comment', [SuggestionController::class, 'comment'])->name('suggestions.comment');
        Route::put('/comments/{comment}', [CommentsSuggestionController::class, 'update'])->name('suggestions.comments.update');
        Route::delete('/comments/{comment}', [CommentsSuggestionController::class, 'destroy'])->name('suggestions.comments.destroy');
    });
});

// Routes d'authentification
require __DIR__ . '/auth.php';