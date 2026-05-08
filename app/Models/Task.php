<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    // These are the fields that can be saved to the database
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'deadline',
        'project_id',
        'user_id',
    ];

    // ====== RELATIONSHIPS ======
    // These methods define connections to other tables

    /**
     * Get the project this task belongs to
     * Example: $task->project->title
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user assigned to this task
     * Example: $task->assignedUser->name
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ====== ACCESSORS ======
    // These are virtual properties that compute values on the fly

    /**
     * Convert status code to human-readable French text
     * Example: 'in_progress' becomes 'En cours'
     * Access it like: $task->status_label
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
     * Check if the task deadline is coming up soon
     * Returns: 'ok', 'urgent' (< 48h), or 'overdue' (past deadline)
     * Access it like: $task->deadline_status
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

    /**
     * Get Bootstrap color class for the status badge
     * Example: 'todo' task shows as gray, 'done' shows as green
     * Access it like: $task->status_badge_color
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'todo'        => 'secondary',
            'in_progress' => 'primary',
            'done'        => 'success',
            default       => 'secondary',
        };
    }

    /**
     * Get Bootstrap color class for the priority badge
     * Example: high priority shows red, low shows blue
     * Access it like: $task->priority_badge_color
     */
    public function getPriorityBadgeColorAttribute(): string
    {
        return match($this->priority) {
            'low'    => 'info',
            'medium' => 'warning',
            'high'   => 'danger',
            default  => 'secondary',
        };
    }

    // ====== QUERY SCOPES ======
    // These help filter tasks quickly

    /**
     * Find tasks that need attention (urgent deadline, not done)
     * Example: Task::urgent()->get()
     * Or: $project->tasks()->urgent()->get()
     */
    public function scopeUrgent(Builder $query): Builder
    {
        return $query->where('status', '!=', 'done')
                     ->where('deadline', '<=', now()->addHours(48));
    }
}
