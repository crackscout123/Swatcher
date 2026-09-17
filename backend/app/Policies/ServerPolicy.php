<?php

namespace App\Policies;

use App\Models\Server;
use App\Models\User;

class ServerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Server $server): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function update(User $user, Server $server): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin']);
    }

    public function delete(User $user, Server $server): bool
    {
        return $user->hasRole('superadmin');
    }
}
