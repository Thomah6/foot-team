<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
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
            'is_published'=> 'boolean'
        ]);

        $validated['user_id'] = auth()->id(); // auteur de la news

        News::create($validated);

        return redirect()->route('admin.news.index')
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
            'is_published'=> 'boolean'
        ]);

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Actualité mise à jour.');
    }

    /**
     * Suppression
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Actualité supprimée.');
    }

    /**
     * Publication / dépublication
     */
    public function toggleBanner($id)
    {
        $news = News::findOrFail($id);

        // On inverse la publication
        $news->is_published = ! $news->is_published;
        $news->save();

        return redirect()->route('admin.news.index')
            ->with('success', 'Statut de publication mis à jour.');
    }
}
