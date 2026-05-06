<x-app-layout>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Project Details Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ $project->title }}</h4>
                    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-secondary">← Retour</a>
                </div>

                <div class="card-body">
                    @if($project->description)
                        <p>{{ $project->description }}</p>
                    @endif

                    @if($project->deadline)
                        <p class="text-muted">
                            <strong>Deadline:</strong> {{ $project->deadline }}
                        </p>
                    @endif

                    <div class="mb-3">
                        <strong>Progression des tâches:</strong>
                        <div class="progress mt-2">
                            @php
                                $tasksCount = $project->tasks->count();
                                $doneTasks = $project->tasks->where('status', 'done')->count();
                                $percentage = $tasksCount > 0 ? round(($doneTasks / $tasksCount) * 100) : 0;
                            @endphp
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $percentage }}%;"
                                 aria-valuenow="{{ $percentage }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                {{ $percentage }}%
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ $doneTasks }} / {{ $tasksCount }} tâches terminées
                        </small>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <a href="{{ route('tasks.index', $project) }}" class="btn btn-primary">Voir les tâches</a>

                    @can('update', $project)
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning">Éditer</a>
                    @endcan
                </div>
            </div>

            <!-- Tasks List Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Tâches du Projet</h5>
                    @can('create', [App\Models\Task::class, $project])
                        <a href="{{ route('tasks.create', $project) }}" class="btn btn-sm btn-primary">
                            + Nouvelle Tâche
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    @if($project->tasks->isEmpty())
                        <p class="text-muted">Aucune tâche pour le moment.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Statut</th>
                                        <th>Priorité</th>
                                        <th>Assigné à</th>
                                        <th>Deadline</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($project->tasks as $task)
                                        <tr>
                                            <td>{{ $task->title }}</td>
                                            <td>
                                                <span class="badge bg-{{ $task->status_badge_color }}">
                                                    {{ $task->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $task->priority_badge_color }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </td>
                                            <td>{{ $task->assignedUser?->name ?? 'Non assigné' }}</td>
                                            <td>
                                                @if($task->deadline)
                                                    {{ $task->deadline }}
                                                    @if($task->deadline_status === 'overdue')
                                                        <span class="text-danger">🔴</span>
                                                    @elseif($task->deadline_status === 'urgent')
                                                        <span class="text-warning">⚠️</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-info">Voir</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="card-footer bg-transparent">
                    <a href="{{ route('tasks.index', $project) }}" class="btn btn-outline-primary">Voir toutes les tâches</a>
                </div>
            </div>

            <!-- Members Management Card -->
            <div class="card">
                <div class="card-header">
                    <h5>Membres du Projet</h5>
                </div>

                <div class="card-body">
                    <!-- Members List -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                @can('update', $project)
                                    <th>Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->members as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $member->pivot->role === 'lead' ? 'primary' : 'secondary' }}">
                                            {{ $member->pivot->role === 'lead' ? 'Lead' : 'Developer' }}
                                        </span>
                                    </td>
                                    @can('update', $project)
                                        <td>
                                            @if($member->id !== auth()->id())
                                                <form action="{{ route('projects.members.remove', [$project, $member]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Retirer {{ $member->name }} du projet ?')">
                                                        Retirer
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">Vous</span>
                                            @endif
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Add Member Form -->
                    @can('update', $project)
                        <hr>
                        <h6>Ajouter un Membre</h6>
                        <form action="{{ route('projects.members.add', $project) }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-8">
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email"
                                       placeholder="Email de l'utilisateur"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success w-100">Ajouter</button>
                            </div>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
