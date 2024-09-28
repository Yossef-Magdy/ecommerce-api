<?php

namespace App\Policies;

use App\Models\Categories\Subcategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubcategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('view subcategories');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SubCategory $subcategory): bool
    {
        return $user->isAdmin() || $user->hasPermission('view subcategories');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('add subcategories');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubCategory $subcategory): bool
    {
        return $user->isAdmin() || $user->hasPermission('update subcategories');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubCategory $subcategory): bool
    {
        return $user->isAdmin() || $user->hasPermission('delete subcategories');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubCategory $subcategory): bool
    {
        return $user->isAdmin() || $user->hasPermission('delete subcategories');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubCategory $subcategory): bool
    {
        return $user->isAdmin() || $user->hasPermission('delete subcategories');
    }
}