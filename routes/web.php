<?php

use App\Http\Controllers\ProfileController;
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
