<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectTaskWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_and_developer_full_flow_and_api_access()
    {
        $lead = User::factory()->create();
        $developer = User::factory()->create();

        $project = Project::create([
            'title' => 'Team Project',
            'description' => 'Workflow test',
            'deadline' => now()->addDays(10)->format('Y-m-d'),
        ]);

        $project->members()->attach($lead->id, ['role' => 'lead']);
        $project->members()->attach($developer->id, ['role' => 'developer']);

        $response = $this->actingAs($lead)
            ->post(route('tasks.store', $project), [
                'title' => 'Build feature',
                'description' => 'Create the assigned feature',
                'deadline' => now()->addDays(3)->format('Y-m-d'),
                'priority' => 'high',
                'user_id' => $developer->id,
            ]);

        $response->assertRedirect(route('tasks.index', $project));

        $task = Task::where('title', 'Build feature')->first();
        $this->assertNotNull($task);
        $this->assertSame('todo', $task->status);
        $this->assertSame($developer->id, $task->user_id);

        $this->actingAs($developer)
            ->get(route('projects.show', $project))
            ->assertStatus(200);

        $this->actingAs($developer)
            ->get(route('projects.edit', $project))
            ->assertStatus(403);

        $this->actingAs($developer)
            ->put(route('tasks.update', $task), [
                'title' => 'Build feature',
                'description' => 'Update attempt',
                'deadline' => now()->addDays(4)->format('Y-m-d'),
                'priority' => 'medium',
                'user_id' => $developer->id,
            ])
            ->assertStatus(403);

        $this->actingAs($developer)
            ->patch(route('tasks.updateStatus', $task), ['status' => 'in_progress'])
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'in_progress',
        ]);

        $this->get("/api/projects/{$project->id}/tasks")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [[
                    'id',
                    'title',
                    'status',
                    'status_label',
                    'priority',
                    'deadline',
                    'deadline_status',
                    'assigned_to',
                ]],
            ]);
    }
}
