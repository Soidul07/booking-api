<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Organization;

class OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('org_admin');
    }

    public function view(User $user, Organization $organization): bool
    {
        return $user->hasRole('super_admin') || $user->organization_id === $organization->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function update(User $user, Organization $organization): bool
    {
        return $user->hasRole('super_admin') || 
               ($user->hasRole('org_admin') && $user->organization_id === $organization->id);
    }

    public function delete(User $user, Organization $organization): bool
    {
        return $user->hasRole('super_admin');
    }

    public function manageTeams(User $user, Organization $organization): bool
    {
        return $user->hasRole('super_admin') || 
               ($user->hasRole('org_admin') && $user->organization_id === $organization->id);
    }
}
