<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DtrLog extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'dtr_logs';

    protected $fillable = [
        'user_id',
        'shift_id',
        'type',
        'recorded_at',
        'work_date',
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
