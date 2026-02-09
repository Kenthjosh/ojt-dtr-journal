<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    //
    protected $fillable = [
        'name',
        'session_1_start',
        'session_1_end',
        'session_2_start',
        'session_2_end',
    ];
}
