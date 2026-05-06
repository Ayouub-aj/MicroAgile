<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\User;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Project::class);

        // Get projects where user is a member, with task counts and members
        $projects = Auth::user()->projects()
                            ->withCount([
                                    'tasks',
                                    'tasks as done_tasks_count' => fn($q) => $q->where('status', 'done')
                                    ])
                            ->with('members')
                            ->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Project::class);

        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        $project = Project::create($request->validated());

        // Attach the creator as a lead member
        $project->members()->attach(Auth::id(), ['role' => 'lead']);

        return redirect()->route('projects.index')
                        ->with('success', 'Projet créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load('members', 'tasks.assignedUser');
        $this->authorize('view', $project);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $project->load('members');
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->load('members');
        $this->authorize('update', $project);

        $project->update($request->validated());

        return redirect()->route('projects.show', $project)
                        ->with('success', 'Projet mis à jour avec succès');
    }

    /**
 * Display archived projects.
 */
public function archives()
{
    $this->authorize('viewAny', Project::class);

    // Get archived projects where user is a lead
    $projects = Project::onlyTrashed()
                    ->whereHas('members', fn($q) =>
                        $q->where('user_id', auth()->id())
                          ->where('role', 'lead')
                    )
                    ->withCount([
                        'tasks',
                        'tasks as done_tasks_count' => fn($q) => $q->where('status', 'done')
                    ])
                    ->with('members')
                    ->get();

    return view('projects.archives', compact('projects'));
}

    /**
     * Restore an archived project.
     */
    public function restore($id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);

        $project->load('members');
        $this->authorize('restore', $project);

        $project->restore();

        return redirect()->route('projects.index')
                        ->with('success', 'Projet restauré avec succès.');
    }

    /**
 * Add a member to the project.
 */
public function addMember(Request $request, Project $project)
{
    $project->load('members');
    $this->authorize('update', $project);

    $validated = $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = \App\Models\User::where('email', $validated['email'])->first();

    // Check if user is already a member
    if ($project->members()->where('user_id', $user->id)->exists()) {
        return back()->withErrors(['email' => 'Cet utilisateur est déjà membre du projet.']);
    }

    $project->members()->attach($user->id, ['role' => 'developer']);

    return back()->with('success', 'Membre ajouté avec succès.');
}

    /**
     * Remove a member from the project.
     */
    public function removeMember(Project $project, \App\Models\User $user)
    {
        $project->load('members');
        $this->authorize('update', $project);

        // Prevent removing self
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas vous retirer vous-même du projet.']);
        }

        $project->members()->detach($user->id);

        return back()->with('success', 'Membre retiré avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->load('members');
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
                        ->with('success', 'Projet archivé avec succès.');
    }
}
