<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklyReports extends Model
{
    /** @use HasFactory<\Database\Factories\WeeklyReportsFactory> */
    use HasFactory;

    use SoftDeletes;

    // Fillable fields
    protected $fillable = [
        'user_id',
        'week_start',
        'week_end',
        'status',
        'submitted_at',
        'viewed_at',
        'certified_at',
        'certified_by',
        'signature',
        'entries',
        'journal_number',
    ];

    public function userWeeklyReports()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // Cast entries as array
    protected $casts = [
        'entries' => 'array',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $table = 'weekly_reports';

    public function certifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'certified_by');
    }

    // Boot method for validation and certified protection
    protected static function booted()
    {
        static::creating(function ($report) {
            self::validateEntries($report->entries);
        });

        static::updating(function ($report) {
            // Check current status in DB, not the new status
            if ($report->getOriginal('status') === 'certified') {
                throw new \Exception('Cannot modify a certified report.');
            }

            // Only validate entries for non-certified reports
            self::validateEntries($report->entries);
        });
    }

    // Full JSON validation
    protected static function validateEntries($entries)
    {
        if (! is_array($entries)) {
            throw new \InvalidArgumentException('Entries must be an array.');
        }

        // week_focus
        if (! array_key_exists('week_focus', $entries) || empty($entries['week_focus'])) {
            throw new \InvalidArgumentException('The "week_focus" key is required in entries.');
        }

        // topics_learned
        if (! array_key_exists('topics_learned', $entries) || ! is_array($entries['topics_learned']) || empty($entries['topics_learned'])) {
            throw new \InvalidArgumentException('The "topics_learned" key is required and cannot be empty.');
        }

        // outputs_links
        if (! array_key_exists('outputs_links', $entries) || ! is_array($entries['outputs_links']) || empty($entries['outputs_links'])) {
            throw new \InvalidArgumentException('The "outputs_links" key is required and cannot be empty.');
        }
        foreach ($entries['outputs_links'] as $link) {
            if (! isset($link['url']) || ! isset($link['description'])) {
                throw new \InvalidArgumentException('Each outputs_links item must have url and description.');
            }
        }

        // what_built
        if (! array_key_exists('what_built', $entries) || empty($entries['what_built'])) {
            throw new \InvalidArgumentException('The "what_built" key is required and cannot be empty.');
        }

        // decisions_reasoning
        if (! array_key_exists('decisions_reasoning', $entries)
            || ! array_key_exists('decision_1', $entries['decisions_reasoning'])
            || ! array_key_exists('decision_2', $entries['decisions_reasoning'])) {
            throw new \InvalidArgumentException('decisions_reasoning must contain decision_1 and decision_2.');
        }

        // challenges_blockers
        if (! array_key_exists('challenges_blockers', $entries) || empty($entries['challenges_blockers'])) {
            throw new \InvalidArgumentException('The "challenges_blockers" key is required and cannot be empty.');
        }

        // improve_next_time
        if (! array_key_exists('improve_next_time', $entries)
            || ! array_key_exists('improvement_1', $entries['improve_next_time'])
            || ! array_key_exists('improvement_2', $entries['improve_next_time'])) {
            throw new \InvalidArgumentException('improve_next_time must contain improvement_1 and improvement_2.');
        }

        // key_takeaway
        if (! array_key_exists('key_takeaway', $entries) || empty($entries['key_takeaway'])) {
            throw new \InvalidArgumentException('The "key_takeaway" key is required and cannot be empty.');
        }
    }

    public function getEntriesDecodedAttribute(): array
    {
        return $this->entries ?? [];
    }
}
