<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
{
    try {
        $googleUser = Socialite::driver('google')->user();
        
        // Vérifier si l'utilisateur existe déjà
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Créer un pseudo à partir du nom
            $pseudo = strtolower(str_replace(' ', '', $googleUser->getName()));
            
            // Créer un nouvel utilisateur avec des valeurs par défaut
            $user = User::create([
                'name' => $googleUser->getName(),
                'pseudo' => $pseudo,
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(Str::random(24)),
                'email_verified_at' => now(),
                'avatar' => $googleUser->getAvatar(), // Ajout de l'avatar depuis Google
            ]);
        } else {
            // Mettre à jour l'utilisateur existant avec l'ID Google
            $user->update([
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
            ]);
        }

        // Connecter l'utilisateur
        Auth::login($user, true);

        return redirect()->intended('/dashboard');

    } catch (\Exception $e) {
        \Log::error('Google Auth Error: ' . $e->getMessage());
        return redirect('/login')->with('error', 'Échec de la connexion avec Google: ' . $e->getMessage());
    }
}
}
