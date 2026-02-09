<?php

namespace App\Traits;

use App\Models\AdminActivities;

trait LogsAdminAction
{
    public function logAdminAction(string $action, $subject): void
    {
        AdminActivities::create([
            'user_id' => auth()->guard()->id(),
            'created_at' => now(),
            'subject_type' => get_class($subject),
            'action' => $action,
            'subject_id' => $subject->id,
        ]);
    }
}
