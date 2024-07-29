<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'author',
        'description',
        'is_done',
        'when_must_done',
        'when_done',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    protected $appends = ['created_at_formatted'];

    // Другие атрибуты и методы

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d.m.Y H:i');
    }
}
