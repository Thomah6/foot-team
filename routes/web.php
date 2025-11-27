<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PlayerOfTheMonthController;

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

Route::prefix('admin/news')->group(function () {

    Route::get('/', [NewsController::class, 'index'])->name('admin.news.index');

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
