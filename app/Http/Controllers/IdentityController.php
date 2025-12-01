<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Identity;

class IdentityController extends Controller
{
    /**
     * Affiche la vue du club avec logo et nom
     */
    public function index(Request $request)
    {
        $identity = Identity::first();

        return Inertia::render('Admin/Identity', [
            'identity' => $identity
        ]);
    }

    /**
     * Enregistre ou met à jour les informations du club
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload du logo si présent
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Soit on crée, soit on met à jour (si une identité existe déjà)
        $identity = Identity::first();

        if ($identity) {
            $identity->update([
                'name' => $request->name,
                'logo' => $logoPath ?? $identity->logo, // garde l'ancien logo si pas changé
            ]);
        } else {
            Identity::create([
                'name' => $request->name,
                'logo' => $logoPath,
            ]);
        }

        return back()->with('success', 'Identité du club enregistrée !');
    }

public function stores(Request $request) {

    $request->validate([
        'name' => 'required|string',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|max:2048',
    ]);

}


public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|max:2048',
    ]);

    $identity = Identity::first() ?? new Identity();

    // Upload du logo si présent
    if ($request->hasFile('logo')) {
        $path = $request->file('logo')->store('logos', 'public');
        $identity->logo = "/storage/" . $path;
    }

    $identity->name = $request->name;
   
    $identity->save();

    return back()->with('success', 'Identité mise à jour');
}

public function deleteIdentity()
{
    $identity = Identity::first();

    if ($identity) {
        // Supprimer le fichier logo si il existe
        if ($identity->logo && file_exists(public_path($identity->logo))) {
            unlink(public_path($identity->logo));
        }

        // Supprimer complètement l'enregistrement
        $identity->delete();
    }

    return back()->with('success', 'Logo et nom supprimés avec succès.');
}





}




// Route::post('/admin/identity/update', function (\Illuminate\Http\Request $request) {

//     $request->validate([
//         'name' => 'required|string',
//         'description' => 'nullable|string',
//         'logo' => 'nullable|image|max:2048',
//     ]);

//     $identity = \App\Models\Identity::first() ?? new \App\Models\Identity();

//     if ($request->hasFile('logo')) {
//         $path = $request->file('logo')->store('logos', 'public');
//         $identity->logo = "/storage/" . $path;
//     }

//     $identity->name = $request->name;
  

//     $identity->save();

//     return back()->with('success', 'Identité mise à jour');
// });

