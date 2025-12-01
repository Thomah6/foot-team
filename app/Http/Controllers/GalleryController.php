<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     // Récupérer toutes les images déjà présentes en base
    //     // $galleries = Gallery::all();
    //     $galleries = Gallery::withCount('likes')->get();
    //     // dd($galleries);
    //     // Envoyer ces données au composant Vue
    //     return Inertia::render('GalleryPage', [
    //         'galleries' => $galleries
    //     ]);

    // }
    public function index()
    {
        $userId = auth()->id();

        $galleries = Gallery::withCount('likes')
            ->get()
            ->map(function ($gallery) use ($userId) {
                $gallery->liked_by_user = $gallery->likes()->where('user_id', $userId)->exists();
                return $gallery;
            });

        return Inertia::render('GalleryPage', [
            'galleries' => $galleries
        ]);
    }

    public function like(Gallery $gallery)
    {
        $user = auth()->user();

        if (!$gallery->likes()->where('user_id', $user->id)->exists()) {
            $gallery->likes()->create(['user_id' => $user->id]);
        }

        // Recharge uniquement les données nécessaires
        return redirect()->route('galleries.index');
    }

    public function unlike(Gallery $gallery)
    {
        $user = auth()->user();

        $gallery->likes()->where('user_id', $user->id)->delete();

        return redirect()->route('galleries.index');
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Méthode pour uploader une nouvelle photo dans la galerie.
     * Accessible par tout utilisateur authentifié (admin ou membre)
     */
    public function store(Request $request)
    {
        //Validation des données envoyées par le formulaire
        $request->validate([
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,gif|max:2048', // fichier obligatoire, doit être une image, max 2MB
            'description' => 'nullable|string|max:255', // description optionnelle
        ]);


        $uploaded = [];

        // 📂 Boucle sur chaque fichier envoyé
        foreach ($request->file('images') as $image) {
            $path = $image->store('galleries', 'public'); // Sauvegarde du fichier dans le dossier "galleries" du disque public (storage/app/public/uploads)

            // 💾 Création d'un nouvel enrégistrement en base de données
            $gallery = Gallery::create([
                'user_id' => auth()->id(), // l'utilisateur connecté est l'uploader
                'image_path' => $path, // chemin du fichier stocké
                'description' => $request->description, // description si fournie
            ]);

            // On stocke l'ID et le chemin
            $uploaded[] = [
                'id' => $gallery->id,
                'image_path' => $gallery->image_path,
            ];
        }

        // Redirection avec message de succès
        // return Inertia::render('GalleryUpload', [
        //     'success' => 'Images uploaded successfully',
        //     'uploaded' => $uploaded
        // ]);

        return redirect()->route('galleries.index')
        ->with('success', 'Images uploaded successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Méthode pour mettre à jour une photo existante.
     * Permet de modifier uniquement la description
     */
    public function update(Request $request, Gallery $gallery)
    {
        //Validation des données envoyées
        $request->validate([
            'description' => 'nullable|string|max:255', // description optionnelle
        ]);

        // Mise à jour de la description en base
        $gallery->update([
            'description' => $request->description,
        ]);

        // Redirection avec message de succès
        return redirect()->back()->with('success', 'Photo updated successfully');
    }

    /**
     * Méthode pour supprimer une photo de la galerie.
     * L'admin peut utilisier pour modérer, ou un membre pour supprimer sa propre photo.
     */
    public function destroy(Gallery $gallery)
    {
        // Suppression du fichier physique dans le disque public
        // Storage::disk('public')->delete($gallery->image_path);

        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        // Suppression de l'enregistrement en base
        $gallery->delete();

        // Redirection avec message de succès
        return redirect()->back()->with('success', 'Photo deleted successfully');
    }
}
