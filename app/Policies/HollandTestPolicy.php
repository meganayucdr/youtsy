<?php

namespace App\Policies;

use App\User;
use App\HollandTest;

/**
 * HollandTest Policy
 */
class HollandTestPolicy extends ModelPolicy
{
    public function showTest(User $user)
    {
        $allow = true;
        return $allow;
    }

}
