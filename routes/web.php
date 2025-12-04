<?php


use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\Admin\TeamStatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Bureau\BureauMemberController;
use App\Http\Controllers\Bureau\BureauStatController;
use App\Http\Controllers\CommentlikeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PlayerOfTheMonthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\RegulationControler;
use App\Http\Controllers\CommentsSuggestionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
// use App\Http\Controllers\IdentityController;
use Inertia\Inertia;

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

// Page d'accueil publique
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    // Récupérer l'identité du club depuis la BDD
    $identity = \App\Models\Identity::first();

    // Si aucune identité n'existe, utiliser des valeurs par défaut sans créer en BDD
    if (!$identity) {
        $clubData = [
            'name' => 'FC Dynamo',
            'logo' => null
        ];
    } else {
        $clubData = [
            'name' => $identity->name,
            'logo' => $identity->logo,
        ];
    }

    return Inertia::render('Welcome', [
        'clubIdentity' => $clubData
    ]);
})->name('home');

// Routes de vote
Route::middleware('auth')->group(function () {
    Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
    Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
    Route::post('/vote/validate', [VoteController::class, 'validateVote'])->name('vote.validate');
    Route::get('/vote/history', [VoteController::class, 'history'])->name('vote.history');
    Route::get('/vote/list/admin', [VoteController::class, 'listAdmin'])->name('vote.list.admin');
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
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::post('/profile/poster', [ProfileController::class, 'updatePoster'])->name('profile.poster.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Présences: accessible to authenticated users (declaration and viewing)
Route::middleware(['auth', 'is.active'])->group(function () {
    Route::get('/presence', [PresenceController::class, 'index'])->name('presence.index');
    Route::get('/presence/history', [PresenceController::class, 'history'])->name('presence.history');
    Route::post('/presence', [PresenceController::class, 'store'])->name('presence.store');
    Route::get('/presence/day', [PresenceController::class, 'getByDate'])->name('presence.getByDate');
});

// Administration
Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Admin dashboard routes
        Route::get('/', [AdminController::class, 'index'])->name('AdminLayout');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');


        // Stats management
        Route::get('/create', [StatController::class, 'create'])->name('CreateStats');


    Route::middleware('role:admin')->group(function () {




        // Identity management
        Route::get('/identity', [IdentityController::class, 'index'])->name('identity');

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






    });



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

        // Mercato
        Route::post('/teams/assign-members', [TeamController::class, 'assignMembers'])->name('assign-members');
        Route::post('/teams/mercato', [TeamController::class, 'mercato'])->name('mercato');

        // Gestion des actualités (Admin)
        Route::prefix('news')->name('news.')->group(function () {
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

//stats des membres bureau
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
//stats des membres admin
    //         Route::prefix('bureau')->middleware('role:admin')->group(function () {
    //     Route::get('/members', [BureauMemberController::class, 'index'])->name('bureau.members.index');

    //     // Statistiques du bureau
    //     Route::prefix('stats')->group(function () {
    //         Route::get('/', [BureauStatController::class, 'index'])->name('bureau.stats.index');
    //         Route::get('/leaderboards', [BureauStatController::class, 'leaderboards'])->name('bureau.stats.leaderboards');
    //         Route::get('/leaderboards/goals', [BureauStatController::class, 'goalLeaders'])->name('bureau.stats.leaderboards.goals');
    //         Route::get('/leaderboards/assists', [BureauStatController::class, 'assistLeaders'])->name('bureau.stats.leaderboards.assists');
    //         Route::get('/leaderboards/goalkeepers', [BureauStatController::class, 'goalkeeperLeaders'])->name('bureau.stats.leaderboards.goalkeepers');
    //         Route::get('/members/{user}/stats', [BureauStatController::class, 'memberStats'])->name('bureau.stats.member');
    //     });
    // });

Route::prefix('reflections')->group(function () {
    Route::get('/', [ReflectionController::class, 'index'])->name('reflections.index');
    Route::get('/{reflection}', [ReflectionController::class, 'show'])->name('reflections.show');
    Route::get('/create', [ReflectionController::class, 'create'])->name('reflections.create');
    Route::post('/', [ReflectionController::class, 'store'])->name('reflections.store');
    Route::get('/{id}/edit', [ReflectionController::class, 'edit'])->name('reflections.edit');
    Route::get('/{reflection}/validate', [ReflectionController::class, 'validateReflection'])->name('reflections.validate');
    Route::put('/{id}', [ReflectionController::class, 'update'])->name('reflections.update');
    Route::delete('/{reflection}', [ReflectionController::class, 'destroy'])->name('reflections.destroy');
    Route::patch('/{id}/toggle', [ReflectionController::class, 'toggle'])->name('reflections.toggle'); // activation/desactivationRoute::post('/{id}/validate', [ReflectionController::class, 'validateAfterDelay'])->name('admin.reflections.validate');
    //Routes concernant les commentaires sur les reflexions
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    //Routes pour les likes des commentaires
    Route::get('/comments/like/{comment}', [CommentlikeController::class, 'like'])->name('likeComment');
    Route::get('/comments/dislike/{comment}', [CommentlikeController::class, 'dislike'])->name('dislikeComment');
});
// Les routes du bureau sont maintenant définies dans la section authentifiée

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
    // Affichage de la galerie
    Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');

    // Page de téléchargement
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


/**
 * 🟢 Stats publiques (consultation libre)
 */
Route::get('/stats', [StatController::class, 'publicIndex'])->name('stats.public.index');
// Finances
Route::prefix('finances')->middleware(['auth', 'is.active'])->group(function () {
    Route::get('/', [FinanceController::class, 'index'])->name('finances.index');
    Route::get('/depot/create', [FinanceController::class, 'createDepot'])->name('finances.createDepot');
    Route::post('/', [FinanceController::class, 'storeDepot'])->name('finances.storeDepot');
    Route::get('/depense/create', [FinanceController::class, 'createDepense'])->name('finances.createDepense');
    Route::post('/depense', [FinanceController::class, 'storeDepense'])->name('finances.storeDepense');
    Route::post('/valider/{id}', [FinanceController::class, 'valider'])->name('finances.valider');
    Route::post('/valider-tous', [FinanceController::class, 'validerTous'])->name('finances.validerTous');
     Route::get('/ajustement', [FinanceController::class, 'createAjustement'])->name('finances.createAjustement');
    Route::post('/ajustement', [FinanceController::class, 'storeAjustement'])->name('finances.storeAjustement');
});


// Routes pour les règlements
Route::middleware(['auth', 'is.active'])->group(function () {

    // Resource pour titres + contenus
    Route::resource('regulations', RegulationControler::class);

    // Routes spécifiques pour les contenus liés

    Route::post('/regulations/fusion', [RegulationControler::class, 'fusion'])->name('regulations.fusion');

    Route::get('/regulations/content/{content}/edit', [RegulationControler::class, 'editContent'])
        ->name('regulations.content.edit');

    Route::put('/regulations/content/{content}', [RegulationControler::class, 'updateContent'])
        ->name('regulations.content.update');

    Route::delete('/regulations/content/{content}', [RegulationControler::class, 'destroyContent'])
        ->name('regulations.content.destroy');
});


// Routes pour les ajustements financiers


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


Route::middleware(['auth'])->group(function () {
    Route::get('/suggestions', [SuggestionController::class, 'index'])->name('suggestions');
    Route::post('/suggestions', [SuggestionController::class, 'store']);
    Route::post('/suggestions/{suggestion}/react', [SuggestionController::class, 'react']);
    Route::post('/suggestions/{suggestion}/comment', [SuggestionController::class, 'comment']);
    Route::delete('/suggestions/{suggestion}', [SuggestionController::class, 'destroy']);
});
// ===== ROUTES ÉQUIPES =====

// Routes publiques (accessible à tout utilisateur authentifié)
Route::middleware(['auth', 'is.active'])->group(function () {
    // Vue simple des équipes et détails
    Route::get('/teams/index', [TeamController::class, 'index'])->name('admin.teams.index');
    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
});

// Routes admin seulement (CRUD complet)
Route::middleware(['auth', 'is.active', 'role:admin'])->group(function () {
    Route::get('/teams/create', [TeamController::class, 'create'])->name('admin.teams.create');
    Route::post('/teams/create', [TeamController::class, 'store'])->name('admin.teams.store');
    Route::get('/teams/{id}/edit', [TeamController::class, 'edit'])->name('admin.teams.edit');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('admin.teams.update');
    Route::delete('/teams/{id}', [TeamController::class, 'destroy'])->name('admin.teams.destroy');
    Route::get('/teams/{team}/affect', [TeamController::class, 'affectPage'])->name('teams.affect');
    Route::post('/teams/{team}/affect/save', [TeamController::class, 'saveAffect'])->name('teams.affect.save');
});

// Routes pour les commentaires des suggestions
Route::put('/comments/{comment}', [CommentsSuggestionController::class, 'update']);
Route::delete('/comments/{comment}', [CommentsSuggestionController::class, 'destroy']);


Route::get('/admin', [AdminController::class, 'index'])->name('Admin.AdminLayout');


Route::get('/admin/create', [StatController::class, 'create'])->name('Admin.CreateStats');

Route::get('/identity', [IdentityController::class, 'index'])->name('admin.identity');


// Route::get('/admin/identity', [IdentityController::class, 'index']->name('admin.indentity'));

Route::post('/admin/identity/update', [IdentityController::class, 'update'])
    ->name('admin.identity.update');
































































































    

Route::post('/admin/identity/delete-identity', [IdentityController::class, 'deleteIdentity'])
    ->name('admin.identity.delete-identity');

// Admin-only management routes for presences
Route::middleware(['auth', 'is.active', 'role:admin'])->group(function () {
    Route::patch('/admin/presence/{presence}/validate', [PresenceController::class, 'validate'])->name('presence.validate');
    Route::patch('/admin/presence/{presence}', [PresenceController::class, 'update'])->name('presence.update');
    Route::get('/admin/presence/monthly-report', [PresenceController::class, 'monthlyReport'])->name('presence.monthlyReport');
});


// Routes pour les réflexions
Route::prefix('reflections')->group(function () {
    Route::get('/', [ReflectionController::class, 'index'])->name('reflections.index');
    Route::get('/{reflection}', [ReflectionController::class, 'show'])->name('reflections.show');
    Route::get('/create', [ReflectionController::class, 'create'])->name('reflections.create');
    Route::post('/', [ReflectionController::class, 'store'])->name('reflections.store');
    Route::get('/{id}/edit', [ReflectionController::class, 'edit'])->name('reflections.edit');
    Route::get('/{id}/validate', [ReflectionController::class, 'validate'])->name('reflections.validate');
    Route::put('/{id}', [ReflectionController::class, 'update'])->name('reflections.update');
    Route::delete('/{id}', [ReflectionController::class, 'destroy'])->name('reflections.destroy');
    Route::patch('/{id}/toggle', [ReflectionController::class, 'toggle'])->name('reflections.toggle'); // activation/desactivationRoute::post('/{id}/validate', [ReflectionController::class, 'validateAfterDelay'])->name('admin.reflections.validate');
    //Routes concernant les commentaires sur les reflexions
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    //Routes pour les likes des commentaires
    // Route::get('/comments/like/{comment}',[CommentlikeController::class,'like'])->name('likeComment');
    // Route::get('/comments/dislike/{comment}',[CommentlikeController::class,'dislike'])->name('dislikeComment');
});




// Routes d'authentification
require __DIR__ . '/auth.php';
