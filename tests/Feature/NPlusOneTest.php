<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class NPlusOneTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_show_does_not_trigger_n_plus_one_queries_for_tasks_and_members()
    {
        $lead = User::factory()->create();
        $developers = User::factory()->count(5)->create();

        $project = Project::create([
            'title' => 'No N+1 project',
            'description' => 'Project used to verify eager loading.',
            'deadline' => now()->addDays(20)->format('Y-m-d'),
        ]);

        $project->members()->attach($lead->id, ['role' => 'lead']);
        foreach ($developers as $developer) {
            $project->members()->attach($developer->id, ['role' => 'developer']);
        }

        foreach (range(1, 10) as $index) {
            Task::create([
                'title' => "Task {$index}",
                'description' => "Description for task {$index}",
                'status' => 'todo',
                'priority' => 'high',
                'deadline' => now()->addDays(3)->format('Y-m-d'),
                'project_id' => $project->id,
                'user_id' => $developers->random()->id,
            ]);
        }

        $executedQueries = [];
        DB::listen(function ($query) use (&$executedQueries) {
            $executedQueries[] = $query->sql;
        });

        $response = $this->actingAs($lead)
            ->get(route('projects.show', $project));

        $response->assertStatus(200);

        $userLookupQueries = array_filter($executedQueries, function ($sql) {
            return stripos($sql, 'from users') !== false && stripos($sql, 'id = ?') !== false;
        });

        $this->assertLessThanOrEqual(3, count($userLookupQueries), 'Project show should not execute a query for every task or member.');
    }

    public function test_tasks_index_does_not_trigger_n_plus_one_queries_for_assigned_users()
    {
        $lead = User::factory()->create();
        $developers = User::factory()->count(5)->create();

        $project = Project::create([
            'title' => 'Tasks index N+1',
            'description' => 'Project used to verify tasks index query count.',
            'deadline' => now()->addDays(18)->format('Y-m-d'),
        ]);

        $project->members()->attach($lead->id, ['role' => 'lead']);
        foreach ($developers as $developer) {
            $project->members()->attach($developer->id, ['role' => 'developer']);
        }

        foreach (range(1, 10) as $index) {
            Task::create([
                'title' => "Task {$index}",
                'description' => "Description for task {$index}",
                'status' => 'todo',
                'priority' => 'high',
                'deadline' => now()->addDays(4)->format('Y-m-d'),
                'project_id' => $project->id,
                'user_id' => $developers->random()->id,
            ]);
        }

        $executedQueries = [];
        DB::listen(function ($query) use (&$executedQueries) {
            $executedQueries[] = $query->sql;
        });

        $response = $this->actingAs($lead)
            ->get(route('tasks.index', $project));

        $response->assertStatus(200);

        $userLookupQueries = array_filter($executedQueries, function ($sql) {
            return stripos($sql, 'from users') !== false && stripos($sql, 'id = ?') !== false;
        });

        $this->assertLessThanOrEqual(3, count($userLookupQueries), 'Tasks index should not execute a query for every assigned user.');
    }
}
