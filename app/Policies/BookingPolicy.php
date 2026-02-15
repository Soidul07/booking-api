<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Booking;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        return $user->hasRole('super_admin') || $user->organization_id === $booking->organization_id;
    }

    public function update(User $user, Booking $booking): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        if ($user->organization_id !== $booking->organization_id) {
            return false;
        }
        
        return $user->hasRole('org_admin') || $booking->created_by === $user->id;
    }

    public function delete(User $user, Booking $booking): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        if ($user->organization_id !== $booking->organization_id) {
            return false;
        }
        
        return $user->hasRole('org_admin') || $booking->created_by === $user->id;
    }

    public function assign(User $user, Booking $booking): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        if ($user->organization_id !== $booking->organization_id) {
            return false;
        }
        
        return $user->hasRole('org_admin');
    }

    public function start(User $user, Booking $booking): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        if ($user->organization_id !== $booking->organization_id) {
            return false;
        }
        
        return $booking->assigned_to === $user->id || $user->hasRole('org_admin');
    }

    public function complete(User $user, Booking $booking): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        if ($user->organization_id !== $booking->organization_id) {
            return false;
        }
        
        return $booking->assigned_to === $user->id || $user->hasRole('org_admin');
    }

    public function cancel(User $user, Booking $booking): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        if ($user->organization_id !== $booking->organization_id) {
            return false;
        }
        
        return $user->hasRole('org_admin') || $booking->created_by === $user->id;
    }
}
