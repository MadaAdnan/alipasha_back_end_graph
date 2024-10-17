<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenderPolicy
{
    use HandlesAuthorization;
/*
 'view_any_tenders',
            'create_tenders',
            'update_tenders',
            'delete_tenders',

            'restore_tenders',
            'force_delete_tenders'
 */

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_tenders');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        return $user->can('view_any_tenders');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_tenders');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->can('update_tenders');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->can('delete_tenders');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_tenders');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->can('force_delete_tenders');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_tenders');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Product $product): bool
    {
        return  $user->can('restore_tenders');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_tenders');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Product $product): bool
    {
        return $user->can('{{ Replicate }}');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('{{ Reorder }}');
    }
}
