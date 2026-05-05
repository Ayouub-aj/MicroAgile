<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        $task->loadMissing('project');

        return $task->project->members()
                    ->where('user_id', $user->id)
                    ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        return $project->members()
                    ->where('user_id', $user->id)
                    ->where('role', 'lead')
                    ->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        $task->loadMissing('project');

        return $task->project->members()
                    ->where('user_id', $user->id)
                    ->where('role', 'lead')
                    ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        $task->loadMissing('project');

        return $task->project->members()
                    ->where('user_id', $user->id)
                    ->where('role', 'lead')
                    ->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }

    public function updateStatus(User $user, Task $task): bool
    {
        // Eager-load the project
        $task->loadMissing('project');

        // User is the assigned developer OR is the lead
        return $task->assigned_to === $user->id
            || $task->project->members()
                ->where('user_id', $user->id)
                ->where('role', 'lead')
                ->exists();
    }
}
