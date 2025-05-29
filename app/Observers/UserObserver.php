<?php

namespace App\Observers;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserObserver
{
    public function created($user)
    {
        // Skip role assignment in testing environment if roles don't exist
        if (app()->environment('testing')) {
            $rolesExist = Role::where('name', 'admin')->orWhere('name', 'user')->exists();
            if (!$rolesExist) {
                return;
            }
        }

        $roleName = User::count() === 1 ? 'admin' : 'user';
        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $user->assignRole($role);
        }
    }
}
