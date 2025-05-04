<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user)
    {

        return true;
        return $user->can('view_any_role');
    }


    public function view()
    {
        return true;
    }

    public function create()
    {
        return true;
    }

    public function update()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }
}
