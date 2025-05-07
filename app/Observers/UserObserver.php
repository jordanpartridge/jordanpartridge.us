<?php

namespace App\Observers;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserObserver
{
    public function created($user)
    {
        $roleName = User::count() === 1 ? 'admin' : 'user';
        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $user->assignRole($role);
        }
    }
}
