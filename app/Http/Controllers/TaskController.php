<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $project->load('members');
        $this->authorize('view', $project);

        $tasks = $project->tasks()->with(['assignedUser', 'project.members'])->get();

        return view('tasks.index', compact('project', 'tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        $project->load('members');
        $this->authorize('create', [Task::class, $project]);

        // Get only developers from the project
        $members = $project->members()->where('role', 'developer')->get();

        return view('tasks.create', compact('project', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, Project $project)
    {
        $project->load('members');
        $this->authorize('create', [Task::class, $project]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'user_id' => $request->user_id,
            'project_id' => $project->id,
            'status' => 'todo', // default status
        ]);

        return redirect()->route('tasks.index', $project)
                        ->with('success', 'Tâche créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load(['project', 'project.members', 'assignedUser']);
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $task->load(['project', 'project.members']);
        $this->authorize('update', $task);

        $project = $task->project;

        // Get only developers from the project
        $members = $project->members()->where('role', 'developer')->get();

        return view('tasks.edit', compact('task', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->load(['project', 'project.members']);
        $this->authorize('update', $task);

        $task->update($request->validated());

        return redirect()->route('tasks.show', $task->project)
                        ->with('success', 'Tâche mise à jour avec succès');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->load(['project', 'project.members']);
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $task->load(['project', 'project.members']);
        $this->authorize('updateStatus', $task);

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update(['status' => $validated['status']]);

        return back()->with('success', 'Statut de la tâche mis à jour avec succès');
    }
}
