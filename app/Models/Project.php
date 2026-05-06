<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'deadline',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // ─── Mutator ──────────────────────────────────────────────────

    //autocapitalize the title when saving
    //e.g. "test project" → "Test project"
    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = ucfirst($value);
    }

    // ─── Scope ────────────────────────────────────────────────────

    // tasks with deadline within 48h that aren't done yet
    public function scopeUrgent(Builder $query): Builder
    {
        return $query->whereHas('tasks', function ($q) {
            $q->where('status', '!=', 'done')
              ->where('deadline', '<=', now()->addHours(48));
        });
    }
}
