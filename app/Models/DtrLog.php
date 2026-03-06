<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Casts\DtrTypeCast;
use Spatie\Activitylog\Models\Activity;


class DtrLog extends Model
{
    use HasFactory;
    use LogsActivity;


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('dtr_log')
            ->logOnly([
                'type',
                'recorded_at',
                'work_date',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "DTR log {$eventName}");
    }

    // Table name
    protected $table = 'dtr_logs';

    protected $casts = [
    'type' => DtrTypeCast::class,
    ];

   public function tapActivity(Activity $activity, string $eventName): void
    {
        $props = $activity->properties ?? collect();

        $attributes = (array) ($props['attributes'] ?? []);
        $old = (array) ($props['old'] ?? []);

        // Add label for NEW value
        if (array_key_exists('type', $attributes)) {
            $attributes['type_label'] = match ((int) $attributes['type']) {
                1 => 'Time In',
                2 => 'Time Out',
                default => (string) $attributes['type'],
            };
        }

        // Add label for OLD value
        if (array_key_exists('type', $old)) {
            $old['type_label'] = match ((int) $old['type']) {
                1 => 'Time In',
                2 => 'Time Out',
                default => (string) $old['type'],
            };
        }

        $props['attributes'] = $attributes;
        $props['old'] = $old;

        $activity->properties = $props;
    }

    protected $fillable = [
        'user_id',
        'shift_id',
        'type',
        'recorded_at',
        'work_date',
        'work_minutes',
        'late_minutes',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
