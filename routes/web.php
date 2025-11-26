<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReflectionController;
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

Route::prefix('admin/reflections')->middleware('role:admin')->group(function () {
    Route::get('/', [ReflectionController::class, 'index'])->name('admin.reflections.index');
    Route::get('/create', [ReflectionController::class, 'create'])->name('admin.reflections.create');
    Route::post('/', [ReflectionController::class, 'store'])->name('admin.reflections.store');
    Route::get('/{id}/edit', [ReflectionController::class, 'edit'])->name('admin.reflections.edit');
    Route::put('/{id}', [ReflectionController::class, 'update'])->name('admin.reflections.update');
    Route::delete('/{id}', [ReflectionController::class, 'destroy'])->name('admin.reflections.destroy');
    Route::patch('/{id}/toggle', [ReflectionController::class, 'toggle'])->name('admin.reflections.toggle'); // activation/desactivationRoute::post('/{id}/validate', [ReflectionController::class, 'validateAfterDelay'])->name('admin.reflections.validate');
});

require __DIR__.'/auth.php';
