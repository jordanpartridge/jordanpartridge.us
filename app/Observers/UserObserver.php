<?php

namespace App\Observers;

use Spatie\Permission\Models\Role;

class UserObserver
{
    public function created($user)
    {
        $defaultRole = Role::where('name', 'user')->first();
        if ($defaultRole) {
            $user->assignRole($defaultRole);
        }
    }
}
