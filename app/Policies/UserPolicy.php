<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->can('view_any_user');
    }


    public function view(User $user)
    {
        return $user->can('view_user');
    }

    public function create(User $user)
    {
        return $user->can('create_user');
    }
    public function restore(User $user)
    {
        return $user->can('restore_user');
    }
    public function forceDelete(User $user)
    {
        return $user->can('force_delete_user');
    }
    public function restoreAny(User $user)
    {
        return $user->can('restore_any_user');
    }
    public function replicate(User $user)
    {
        return $user->can('replicate_user');
    }


    public function update(User $user)
    {
        return $user->can('update_user');
    }
}
