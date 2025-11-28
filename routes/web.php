<?php

use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentlikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TeamStatController;
use App\Http\Controllers\Bureau\BureauMemberController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Bureau\BureauStatController;
use App\Http\Controllers\Admin\MemberController;

use App\Models\Commentlike;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoteController;
use Inertia\Inertia;
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
Route::middleware('auth')->group(function () {
    Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');

    // Route pour enregistrer un vote
    Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');

    // Route pour valider un vote (admin uniquement)
    Route::post('/vote/validate', [VoteController::class, 'validateVote'])->name('vote.validate');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'is.active'])
    ->name('dashboard');



Route::middleware(['auth', 'is.active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware('role:admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');

  

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

        //ROUTES POUR LES STATS MANUELLES A RENTRER PAR L'admin

        Route::get('/stats', [StatController::class,'index'])->name('admin.stats.index');
        Route::get('/stats/pending',[StatController::class,'pending'])->name('admin.stats.pending');
        
        //Routes pour les stats d'équipes
        Route::get('/team-stats',[TeamStatController::class,'index'])->name('admin.team-stats.index');
        Route::get('/team-stats/create',[TeamStatController::class,'create'])->name('admin.team-stats.create');
        Route::post('/team-stats',[TeamStatController::class,'store'])->name('admin.team-stats.store');
        Route::get('/team-stats/{teamStat}/edit',[TeamStatController::class,'edit'])->name('admin.team-stats.edit');
        Route::patch('/team-stats/{teamStat}',[TeamStatController::class,'update'])->name('admin.team-stats.update');

        Route::delete('/team-stats/{teamStat}',[TeamStatController::class,'destroy'])->name('admin.team-stats.destroy');

        Route::get('/team-stats/team/{team}',[TeamStatController::class,'byTeam'])->name('admin.team-stats.by-team');

        Route::get('/team-stats/api/current-month',[TeamStatController::class,'currentMonthStats'])->name('admin.team-stats.current-month-api');

        Route::post('/team-stats/bulk-validate',[TeamStatController::class,'bulkValidate'])->name('admin.team-stats.bulk-validate');

    });

    Route::prefix('bureau/members')->middleware('role:bureau')->group(function(){
        Route::get('/',[BureauMemberController::class,'index'])->name('bureau.members.index');
    });
});

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
    Route::post('/comments',[CommentController::class,'store'])->name('comments.store');

    //Routes pour les likes des commentaires
    Route::get('/comments/like/{id}',[CommentlikeController::class,'like'])->name('likeComment');
    Route::get('/comments/dislike/{id}',[CommentlikeController::class,'dislike'])->name('dislikeComment');
});

/**
 * 🟢 Stats publiques (consultation libre)
 */
Route::get('/stats', [StatController::class, 'publicIndex'])->name('stats.public.index');


 


Route::get('/admin', [AdminController::class,'index'])->name('Admin.AdminLayout');


Route::get('/admin/create', [StatController::class,'create'])->name('Admin.CreateStats');
/**
 * 📊 Stats admin (accès authentifié)
 */
Route::get('/admin/stats', [StatController::class, 'index'])
    ->middleware(['auth', 'is.active'])
    ->name('admin.stats.index');

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

        Route::get('/stats', [StatController::class, 'index'])
            ->name('stats.index');

        Route::get('/stats/classements', [StatController::class, 'adminClassementsIndex'])
            ->name('stats.classements');

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
Route::post('/teams/{team}/affect/save', [TeamController::class, 'saveAffect']);



Route::prefix('bureau/stats')->middleware('role:bureau')->group(function () {
    Route::get('/', [BureauStatController::class, 'index'])->name('bureau.stats.index');

    Route::get('/leaderboards', [BureauStatController::class, 'leaderboards'])->name('bureau.stats.leaderboards');

    Route::get('/leaderboards/goals', [BureauStatController::class, 'goalLeaders'])->name('bureau.stats.leaderboards.goals');

    Route::get('/leaderboards/assists', [BureauStatController::class, 'assistLeaders'])->name('bureau.stats.leaderboards.assists');

    Route::get('/leaderboards/goalkeepers', [BureauStatController::class, 'goalkeeperLeaders'])->name('bureau.stats.leaderboards.goalkeepers');
    
    Route::get('/members/{user}/stats', [BureauStatController::class, 'memberStats'])->name('bureau.stats.member');
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
