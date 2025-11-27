<?php

use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProfileController;
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


require __DIR__.'/auth.php';
