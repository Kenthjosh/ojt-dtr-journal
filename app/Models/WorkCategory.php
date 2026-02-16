<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users;

class WorkCategory extends Model
{
    use HasFactory;

    protected $fillable = ["name", "created_by"];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, "created_by");
    }
}
