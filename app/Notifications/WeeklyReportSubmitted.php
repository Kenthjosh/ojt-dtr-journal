<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\WeeklyReports;

class WeeklyReportSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected WeeklyReports $report;

    public function __construct(WeeklyReports $report)
    {
        $this->report = $report;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'report_id'   => $this->report->id,
            'intern_name' => optional($this->report->user)->name ?? 'Unknown Intern',
            'message'     => 'A new weekly report has been submitted.',
            'url'         => route('filament.admin.resources.weekly-reports.edit', $this->report->id),
        ];
    }
}