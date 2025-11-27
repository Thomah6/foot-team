<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegulationControler;
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
    Route::resource('regulations', RegulationControler::class);
    Route::get('/regulations/create-title', [RegulationControler::class, 'createTitle'])->name('regulations.createTitle');
    Route::post('/regulations/title', [RegulationControler::class, 'storeTitle'])->name('regulations.storeTitle');
    Route::post('/regulations/content', [RegulationControler::class, 'storeContent'])->name('regulations.storeContent');
});

require __DIR__.'/auth.php';
