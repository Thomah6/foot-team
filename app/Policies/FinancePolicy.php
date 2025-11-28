<?php

namespace App\Policies;

use App\Models\Finance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinancePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any finances (list).
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isBureau() || $user->isSimple();
    }

    /**
     * Determine whether the user can view a specific finance record.
     */
    public function view(User $user, Finance $finance): bool
    {
        return $user->isAdmin() || $user->isBureau() || $finance->user_id === $user->id;
    }

    /**
     * Determine whether the user can validate a single deposit.
     */
    public function valider(User $user, Finance $finance): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can validate all pending deposits.
     * This is a class-level check (no specific Finance instance required).
     */
    public function validerTous(User $user): bool
    {
        return $user->isAdmin();
    }

    public function ajustement(User $user): bool
    {
        return $user->isAdmin();
    }

    // Peut créer une dépense (admin ou bureau)
    public function createDepense(User $user)
    {
        return in_array($user->role, ['admin', 'bureau']);
    }

    // Validation auto: uniquement admin
    public function autoValideDepense(User $user)
    {
        return $user->role === 'admin';
    }

}
