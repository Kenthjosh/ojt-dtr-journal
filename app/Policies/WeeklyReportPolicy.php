<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WeeklyReports;

class WeeklyReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WeeklyReports $weeklyReport): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WeeklyReports $weeklyReport): bool
    {
        return $user->role === 'admin' && $weeklyReport->certified_at === null;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WeeklyReports $weeklyReport): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WeeklyReports $weeklyReport): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WeeklyReports $weeklyReport): bool
    {
        return false;
    }
}
