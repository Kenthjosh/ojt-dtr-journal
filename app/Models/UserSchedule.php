<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSchedule extends Model
{
    //
    protected $fillable = [
        'user_id',
        'shift_id',
        'effective_from',
        'effective_to',
    ];
}
