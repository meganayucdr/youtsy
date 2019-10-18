<?php

namespace App\Policies;

use App\User;
use App\Career;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Career Policy
 */
class CareerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view list of model.
     *
     * @param  App\Career  $career
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->role_id == 2; // TODO: Change as needed, but leave it true if no policy
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\User  $user
     * @param  App\Career  $career
     * @return mixed
     */
    public function view(User $user, Career  $career)
    {
        return $user->role_id == 2; // TODO: Change as needed, but leave it true if no policy
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role_id == 2; // TODO: Change as needed, but leave it true if no policy
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\User  $user
     * @param  \App\Career  $career
     * @return mixed
     */
    public function update(User $user, Career  $career)
    {
        return $user->role_id == 2; // TODO: Change as needed, but leave it true if no policy
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\User  $user
     * @param  \App\Career  $career
     * @return mixed
     */
    public function delete(User $user, Career  $career)
    {
        return $user->role_id == 2; // TODO: Change as needed, but leave it true if no policy
    }
}
