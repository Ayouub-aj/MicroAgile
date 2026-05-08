<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class QaAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_requests_are_used_in_project_and_task_controllers()
    {
        $expected = [
            [ProjectController::class, 'store', StoreProjectRequest::class],
            [ProjectController::class, 'update', UpdateProjectRequest::class],
            [TaskController::class, 'store', StoreTaskRequest::class],
            [TaskController::class, 'update', UpdateTaskRequest::class],
        ];

        foreach ($expected as [$class, $method, $requestClass]) {
            $reflection = new \ReflectionMethod($class, $method);
            $parameter = $reflection->getParameters()[0];
            $this->assertSame($requestClass, $parameter->getType()->getName());
        }
    }

    public function test_csrf_token_is_present_in_project_and_task_forms()
    {
        $lead = User::factory()->create();

        $project = Project::create([
            'title' => 'QA Project',
            'description' => 'For CSRF audit',
            'deadline' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $project->members()->attach($lead->id, ['role' => 'lead']);

        $task = Task::create([
            'title' => 'QA Task',
            'description' => 'Task CSRF check',
            'status' => 'todo',
            'priority' => 'medium',
            'deadline' => now()->addDays(3)->format('Y-m-d'),
            'project_id' => $project->id,
            'user_id' => $lead->id,
        ]);

        $this->actingAs($lead)
            ->get(route('projects.create'))
            ->assertStatus(200)
            ->assertSee('name="_token"', false);

        $this->actingAs($lead)
            ->get(route('projects.edit', $project))
            ->assertStatus(200)
            ->assertSee('name="_token"', false);

        $this->actingAs($lead)
            ->get(route('projects.show', $project))
            ->assertStatus(200)
            ->assertSee('name="_token"', false);

        $this->actingAs($lead)
            ->get(route('tasks.create', $project))
            ->assertStatus(200)
            ->assertSee('name="_token"', false);

        $this->actingAs($lead)
            ->get(route('tasks.edit', $task))
            ->assertStatus(200)
            ->assertSee('name="_token"', false);

        $project->delete();

        $this->actingAs($lead)
            ->get(route('projects.archives'))
            ->assertStatus(200)
            ->assertSee('name="_token"', false);
    }

    public function test_models_define_expected_fillable_fields()
    {
        $this->assertSame([
            'title',
            'description',
            'deadline',
        ], (new Project())->getFillable());

        $this->assertSame([
            'title',
            'description',
            'status',
            'priority',
            'deadline',
            'project_id',
            'user_id',
        ], (new Task())->getFillable());

        $this->assertSame([
            'name',
            'email',
            'password',
        ], (new User())->getFillable());
    }

    public function test_controllers_do_not_use_abort_403()
    {
        $controllerFiles = File::files(app_path('Http/Controllers'));

        foreach ($controllerFiles as $file) {
            $contents = File::get($file);
            $this->assertStringNotContainsString('abort(403)', $contents, "Found abort(403) in {$file}");
        }
    }

    public function test_urgent_scope_filters_only_uncompleted_tasks_due_soon()
    {
        $project = Project::create([
            'title' => 'Urgent Scope Project',
            'description' => 'Scope test',
            'deadline' => now()->addDays(10)->format('Y-m-d'),
        ]);

        $project->members()->attach(User::factory()->create()->id, ['role' => 'lead']);

        Task::create([
            'title' => 'Overdue task',
            'description' => 'Past deadline',
            'status' => 'todo',
            'priority' => 'high',
            'deadline' => now()->subDay()->format('Y-m-d'),
            'project_id' => $project->id,
            'user_id' => null,
        ]);

        Task::create([
            'title' => 'Urgent task',
            'description' => 'Due soon',
            'status' => 'todo',
            'priority' => 'high',
            'deadline' => now()->addHours(24)->format('Y-m-d'),
            'project_id' => $project->id,
            'user_id' => null,
        ]);

        Task::create([
            'title' => 'Not urgent task',
            'description' => 'Later deadline',
            'status' => 'todo',
            'priority' => 'low',
            'deadline' => now()->addDays(5)->format('Y-m-d'),
            'project_id' => $project->id,
            'user_id' => null,
        ]);

        Task::create([
            'title' => 'Done task',
            'description' => 'Finished task',
            'status' => 'done',
            'priority' => 'medium',
            'deadline' => now()->addHours(24)->format('Y-m-d'),
            'project_id' => $project->id,
            'user_id' => null,
        ]);

        $this->assertSame(2, Task::urgent()->count());
    }
}
