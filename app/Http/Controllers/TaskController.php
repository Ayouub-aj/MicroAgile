<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with(['project', 'project.members', 'assignedUser'])
        ->whereHas('project.members', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->get();

    return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get projects where user is lead
        $projects = Project::with('members')
            ->whereHas('members', function ($query) {
                $query->where('user_id', Auth::id())
                    ->where('role', 'lead');
            })
            ->get();

        return view('tasks.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project = Project::with('members')->findOrFail($request->project_id);
        $this->authorize('create', [Task::class, $project]);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create($validated);

        return redirect()->route('tasks.show', $task);

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

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $task->load(['project', 'project.members']);
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.show', $task);
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

        $task->update($validated);

        return back();
    }
}
