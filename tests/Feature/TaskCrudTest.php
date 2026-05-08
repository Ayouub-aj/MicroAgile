<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_crud_lifecycle_for_project_lead_and_developer()
    {
        $lead = User::factory()->create();
        $developer = User::factory()->create();

        $project = Project::create([
            'title' => 'Task project',
            'description' => 'Project for task CRUD tests.',
            'deadline' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $project->members()->attach($lead->id, ['role' => 'lead']);
        $project->members()->attach($developer->id, ['role' => 'developer']);

        $this->actingAs($lead)
            ->get(route('tasks.create', $project))
            ->assertStatus(200)
            ->assertSee('Créer')
            ->assertSee('Assigner à');

        $this->actingAs($lead)
            ->post(route('tasks.store', $project), [
                'title' => 'Build feature',
                'description' => 'Create the main feature.',
                'deadline' => now()->addDays(5)->format('Y-m-d'),
                'priority' => 'high',
                'user_id' => $developer->id,
            ])
            ->assertRedirect(route('tasks.index', $project));

        $task = Task::where('title', 'Build feature')->first();
        $this->assertNotNull($task);
        $this->assertSame($developer->id, $task->user_id);
        $this->assertSame('todo', $task->status);

        $this->actingAs($lead)
            ->get(route('tasks.show', $task))
            ->assertStatus(200)
            ->assertSee('Build feature');

        $this->actingAs($lead)
            ->get(route('tasks.edit', $task))
            ->assertStatus(200)
            ->assertSee('Build feature');

        $this->actingAs($lead)
            ->put(route('tasks.update', $task), [
                'title' => 'Build feature v2',
                'description' => 'Update the feature task.',
                'deadline' => now()->addDays(7)->format('Y-m-d'),
                'priority' => 'medium',
                'user_id' => $developer->id,
            ])
            ->assertRedirect(route('tasks.index', $project));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Build feature v2',
            'priority' => 'medium',
        ]);

        $this->actingAs($lead)
            ->patch(route('tasks.updateStatus', $task), [
                'status' => 'done',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'done',
        ]);

        $this->actingAs($lead)
            ->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index', $project));

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
