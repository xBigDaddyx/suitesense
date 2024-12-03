<?php

namespace App\Policies\Vendor;

use App\Models\User;
use App\Models\Vendor\License;
use Illuminate\Auth\Access\HandlesAuthorization;

class LicensePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_license');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, License $license): bool
    {
        return $user->can('view_license');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_license');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, License $license): bool
    {
        return $user->can('update_license');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, License $license): bool
    {
        return $user->can('delete_license');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_license');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, License $license): bool
    {
        return $user->can('force_delete_license');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_license');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, License $license): bool
    {
        return $user->can('restore_license');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_license');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, License $license): bool
    {
        return $user->can('replicate_license');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_license');
    }
}
