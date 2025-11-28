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
            \Log::info('Début du callback Google');
            $googleUser = Socialite::driver('google')->user();
            \Log::info('Utilisateur Google récupéré', ['email' => $googleUser->getEmail(), 'id' => $googleUser->getId()]);
            
            // Vérifier si l'utilisateur existe déjà
            $user = User::where('email', $googleUser->getEmail())->first();
            \Log::info('Recherche utilisateur existant', ['trouve' => $user ? 'oui' : 'non']);

            if (!$user) {
                // Créer un pseudo à partir du nom
                $pseudo = strtolower(str_replace(' ', '', $googleUser->getName()));
                
                // Vérifier si le pseudo existe déjà
                $pseudoBase = $pseudo;
                $counter = 1;
                while (User::where('pseudo', $pseudo)->exists()) {
                    $pseudo = $pseudoBase . $counter;
                    $counter++;
                }
                
                // Créer un nouvel utilisateur avec des valeurs par défaut
                $userData = [
                    'name' => $googleUser->getName(),
                    'pseudo' => $pseudo,
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(24)),
                    'email_verified_at' => now(),
                    'avatar' => $googleUser->getAvatar(),
                    'is_active' => true,
                ];
                
                \Log::info('Tentative de création utilisateur', $userData);
                
                try {
                    $user = User::create($userData);
                    \Log::info('Utilisateur créé avec succès', ['user_id' => $user->id]);
                } catch (\Exception $e) {
                    \Log::error('Erreur création utilisateur', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            } else {
                // Mettre à jour l'utilisateur existant avec l'ID Google
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                ]);
                \Log::info('Utilisateur mis à jour avec Google ID', ['user_id' => $user->id]);
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
