<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\WeeklyReports;

class WeeklyReportSubmitted extends Notification
{
    use Queueable;

    protected WeeklyReports $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(WeeklyReports $report)
    {
        $this->report = $report;
    }

    /**
     * Only database channel for this notification.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Store data in the database.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'report_id'   => $this->report->id,
            'intern_name' => $this->report->user->name,
            'message'     => 'A new weekly report has been submitted.',
        ];
    }
}
