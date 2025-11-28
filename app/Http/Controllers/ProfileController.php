<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = $request->user();

        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        // Debug pour vérifier la sauvegarde
        \Log::info('Avatar saved for user ' . $user->id . ': ' . $path);

        return Redirect::route('profile.edit')->with('success', 'Avatar mis à jour avec succès!');
    }

    /**
     * Update the user's poster.
     */
    public function updatePoster(Request $request): RedirectResponse
    {
        $request->validate([
            'poster' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ]);

        $user = $request->user();

        // Delete old poster if exists
        if ($user->poster) {
            Storage::disk('public')->delete($user->poster);
        }

        // Store new poster
        $path = $request->file('poster')->store('posters', 'public');
        $user->poster = $path;
        $user->save();

        // Debug pour vérifier la sauvegarde
        \Log::info('Poster saved for user ' . $user->id . ': ' . $path);

        return Redirect::route('profile.edit')->with('success', 'Poster mis à jour avec succès!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
