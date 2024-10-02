<?php

namespace App\Policies;

use App\Models\Governorate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GovernoratePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-governorates');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('add-governorates');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Governorate $governorate): bool
    {
        return $user->hasPermission('update-governorates');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Governorate $governorate): bool
    {
        return $user->hasPermission('delete-governorates');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Governorate $governorate): bool
    {
        return $user->hasPermission('delete-governorates');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Governorate $governorate): bool
    {
        return $user->hasPermission('delete-governorates');
    }
}