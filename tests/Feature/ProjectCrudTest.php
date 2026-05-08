<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Project;
use App\Models\User;

class ProjectCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_crud_and_archive_restore_force_delete_workflow()
    {
        $lead = User::factory()->create();

        $this->actingAs($lead)
            ->post(route('projects.store'), [
                'title' => 'New sample project',
                'description' => 'A detailed description for the new project.',
                'deadline' => now()->addDays(10)->format('Y-m-d'),
            ])
            ->assertRedirect(route('projects.index'));

        $project = Project::first();

        $this->assertNotNull($project);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => ucfirst('new sample project'),
        ]);
        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $lead->id,
            'role' => 'lead',
        ]);

        $this->actingAs($lead)
            ->get(route('projects.index'))
            ->assertStatus(200)
            ->assertSee('New sample project');

        $this->actingAs($lead)
            ->get(route('projects.show', $project))
            ->assertStatus(200)
            ->assertSee('New sample project');

        $this->actingAs($lead)
            ->get(route('projects.edit', $project))
            ->assertStatus(200)
            ->assertSee('New sample project');

        $this->actingAs($lead)
            ->put(route('projects.update', $project), [
                'title' => 'Updated sample project',
                'description' => 'Updated description.',
                'deadline' => now()->addDays(15)->format('Y-m-d'),
            ])
            ->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Updated sample project',
        ]);

        $this->actingAs($lead)
            ->patch(route('projects.archive', $project))
            ->assertRedirect(route('projects.index'));

        $this->assertSoftDeleted('projects', [
            'id' => $project->id,
        ]);

        $this->actingAs($lead)
            ->get(route('projects.archives'))
            ->assertStatus(200)
            ->assertSee('Updated sample project');

        $this->actingAs($lead)
            ->patch(route('projects.restore', $project->id))
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'deleted_at' => null,
        ]);

        $this->actingAs($lead)
            ->patch(route('projects.archive', $project))
            ->assertRedirect(route('projects.index'));

        $this->actingAs($lead)
            ->delete(route('projects.force-delete', $project->id))
            ->assertRedirect(route('projects.archives'));

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }

    public function test_project_member_management_add_and_remove_member()
    {
        $lead = User::factory()->create();
        $developer = User::factory()->create();

        $project = Project::create([
            'title' => 'Member management project',
            'description' => 'Test adding and removing project members.',
            'deadline' => now()->addDays(12)->format('Y-m-d'),
        ]);

        $project->members()->attach($lead->id, ['role' => 'lead']);

        $this->actingAs($lead)
            ->post(route('projects.members.add', $project), [
                'user_id' => $developer->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $developer->id,
            'role' => 'developer',
        ]);

        $this->actingAs($lead)
            ->delete(route('projects.members.remove', [$project, $developer]))
            ->assertRedirect();

        $this->assertDatabaseMissing('project_user', [
            'project_id' => $project->id,
            'user_id' => $developer->id,
        ]);
    }
}
