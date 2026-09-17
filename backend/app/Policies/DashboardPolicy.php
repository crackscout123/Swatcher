<?php

namespace App\Policies;

use App\Models\Dashboard;
use App\Models\User;

class DashboardPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Dashboard $dashboard): bool
    {
        return $user->id === $dashboard->user_id || $user->hasRole('admin') || $user->hasRole('superadmin');
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['superadmin', 'admin', 'editor']);
    }

    public function update(User $user, Dashboard $dashboard): bool
    {
        return $user->id === $dashboard->user_id || $user->hasRole('admin') || $user->hasRole('superadmin');
    }

    public function delete(User $user, Dashboard $dashboard): bool
    {
        return $user->id === $dashboard->user_id || $user->hasRole('superadmin');
    }
}
