<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class NewsController extends Controller
{
    /**
     * Liste des actualités
     */
    public function index()
    {
        $news = News::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/News/Index', [
            'news' => $news
        ]);
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        return Inertia::render('Admin/News/Create');
    }

    public function showReglement()
    {
        return Inertia::render('Admin/News/ReglementSlider');
    }

    public function PlayerOfTheMonth()
    {
        return Inertia::render('Admin/News/BannerPlayerMonth');
    }

    /**
     * Enregistrement d’une actualité
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'is_published' => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        // Upload de l’image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('news', 'public');
        }

        // Auteur
        $validated['user_id'] = auth()->id();

        News::create($validated);

        return redirect()->route('news.index')
            ->with('success', 'Actualité créée avec succès.');
    }

    /**
     * Formulaire édition
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);

        return Inertia::render('Admin/News/Edit', [
            'news' => $news
        ]);
    }

    /**
     * Mise à jour d’une actualité
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'is_published' => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        // Si une nouvelle image est uploadée → on supprime l’ancienne
        if ($request->hasFile('image')) {

            // Suppression de l'ancienne si existe
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }

            // Nouvelle image
            $validated['image'] = $request->file('image')
                ->store('news', 'public');
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $news->image = $path;
        }
        

        $news->update($validated);

        return redirect()->route('news.index')
            ->with('success', 'Actualité mise à jour.');
    }

    /**
     * Suppression
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);

        // Supprimer l’image associée si elle existe
        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'Actualité supprimée.');
    }

    /**
     * Publication / dépublication
     */
    public function toggleBanner($id)
    {
        $news = News::findOrFail($id);

        $news->is_published = ! $news->is_published;
        $news->save();

        return redirect()->route('news.index')
            ->with('success', 'Statut de publication mis à jour.');
    }
}
