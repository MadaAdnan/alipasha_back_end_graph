<?php

namespace App\Policies;

use App\Models\ClickWhats;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClickWhatsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_click::whats');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClickWhats $clickWhats): bool
    {
        return $user->can('{{ View }}');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_click::whats');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClickWhats $clickWhats): bool
    {
        return $user->can('update_click::whats');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClickWhats $clickWhats): bool
    {
        return $user->can('delete_click::whats');
    }


    public function deleteAny(User $user):bool
    {
        return $user->can('{{ DeleteAny }}');
    }
    public function forceDelete(User $user,  ClickWhats $clickWhats): bool
    {
        return $user->can('force_delete_click::whats');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user,  ClickWhats $clickWhats): bool
    {
        return $user->can('restore_click::whats');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('{{ RestoreAny }}');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ClickWhats $clickWhats): bool
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
