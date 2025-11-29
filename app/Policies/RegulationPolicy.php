<?php

namespace App\Policies;

use App\Models\Regulation;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class RegulationPolicy
{
    /**
     * Tout le monde peut voir la liste.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Tout le monde peut voir un règlement.
     */
    public function view(User $user, Regulation $regulation): bool
    {
        return true;
    }

    /**
     * Seul l’admin peut créer.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Seul l’admin peut mettre à jour.
     */
    public function update(User $user, Regulation $regulation): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Seul l’admin peut supprimer.
     */
    public function delete(User $user, Regulation $regulation): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Regulation $regulation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Regulation $regulation): bool
    {
        return false;
    }
}
