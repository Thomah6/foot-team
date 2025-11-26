<?php

namespace App\Policies;

use App\Models\Finance;
use App\Models\User;

class FinancePolicy
{
    
    public function view(User $user, Finance $finance)
    {
        return $user->is_admin || $user->is_bureau || $finance->user_id === $user->id;
    }

    public function valider(User $user, Finance $finance)
    {
        return $user->is_admin;
    }

}
