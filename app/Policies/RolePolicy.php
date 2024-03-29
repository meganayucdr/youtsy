<?php

namespace App\Policies;

use App\User;
use App\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Role Policy
 */
class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view list of model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return true; // TODO: Change as needed, but leave it true if no policy
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        return true; // TODO: Change as needed, but leave it true if no policy
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true; // TODO: Change as needed, but leave it true if no policy
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        return true; // TODO: Change as needed, but leave it true if no policy
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        return true; // TODO: Change as needed, but leave it true if no policy
    }

}
