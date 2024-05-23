<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $authenticatedUser)
    {
        // Check if the user has the 'view players' permission.
        if (!$authenticatedUser->hasPermissionTo('view players')) {
            return Response::deny('You do not have the required permission to view users.');
        }

        // Check if the user has the 'super-admin' or 'moderator' role.
        if (!$authenticatedUser->hasRole('super-admin') && !$authenticatedUser->hasRole('moderator')) {
            return Response::deny('You do not have the required role to view users.');
        }

        // If the check passes, allow the action.
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authenticatedUser, User $user)
    {
        // First, check if the authenticated user's UUID matches the user's UUID in the request.
        if ($authenticatedUser->uuid !== $user->uuid) {
            return Response::deny('Your UUID does not match the UUID in the request.');
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authenticatedUser, User $user): Response
    {
        // First, check if the authenticated user's UUID matches the user's UUID in the request.
        if ($authenticatedUser->uuid !== $user->uuid) {
            return Response::deny('Your UUID does not match the UUID in the request.');
        }

        // Next, check if the authenticated user has the 'edit nickname' permission.
        if (!$authenticatedUser->hasPermissionTo('edit nickname')) {
            return Response::deny('You do not have the required permission to edit your nickname.');
        }

        // Finally, check if the authenticated user has the 'player' role.
        if (!$authenticatedUser->hasRole('player')) {
            return Response::deny('You do not have the required role to edit players.');
        }

        // If all checks pass, allow the action.
        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
