<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class VoteController extends Controller
{
    /**
     * Afficher le formulaire de vote
     */
    public function index()
    {
        return Inertia::render('Vote/Index');
    }

    /**
     * Enregistrer un vote
     */
    public function store(Request $request)
    {
        // Logique pour enregistrer un vote
        return back()->with('success', 'Votre vote a été enregistré avec succès.');
    }

    /**
     * Valider un vote (pour les administrateurs)
     */
    public function validateVote(Request $request)
    {
        // Logique pour valider un vote
        return back()->with('success', 'Le vote a été validé avec succès.');
    }
}
