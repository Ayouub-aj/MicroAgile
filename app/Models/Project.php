<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'description', 'deadline'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = ucfirst($value);
    }

    public function scopeUrgent(Builder $query)
    {
        return $query->whereHas('tasks', function ($q) {
            $q->where('status', 'pending')
                ->where('deadline', '<=', now()->addHours(48))
                ->where('status', '!=', 'done');
        });
    }
}
