<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'author',
        'project_id',
        'description',
        'is_done',
        'when_must_done',
        'when_done',
    ];
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    protected $appends = ['created_at_formatted'];

    // Другие атрибуты и методы

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d.m.Y H:i');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author');
    }
}
