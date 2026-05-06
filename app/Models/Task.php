<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'deadline',
        'project_id',
        'user_id',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Accessors ────────────────────────────────────────────────

    /**
     * Human-readable status label in French
     * Usage: $task->status_label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'todo'        => 'À faire',
            'in_progress' => 'En cours',
            'done'        => 'Terminé',
            default       => $this->status,
        };
    }

    /**
     * Urgency level based on deadline
     * Usage: $task->deadline_status
     */
    public function getDeadlineStatusAttribute(): string
    {
        if (!$this->deadline || $this->status === 'done') {
            return 'ok';
        }

        $deadline = \Carbon\Carbon::parse($this->deadline);

        if ($deadline->isPast()) {
            return 'overdue';
        }

        if ($deadline->diffInHours(now()) <= 48) {
            return 'urgent';
        }

        return 'ok';
    }

    // ─── Scope ────────────────────────────────────────────────────

    /**
     * Filter tasks that are urgent (not done + deadline within 48h)
     * Usage: Task::urgent()->get() or $project->tasks()->urgent()->get()
     */
    public function scopeUrgent(Builder $query): Builder
    {
        return $query->where('status', '!=', 'done')
                     ->where('deadline', '<=', now()->addHours(48));
    }

    /**
     * Badge color for status
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'todo' => 'secondary',
            'in_progress' => 'primary',
            'done' => 'success',
            default => 'secondary',
        };
    }

    /**
     * Badge color for priority
     */
    public function getPriorityBadgeColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'info',
            'medium' => 'warning',
            'high' => 'danger',
            default => 'secondary',
        };
    }
}
