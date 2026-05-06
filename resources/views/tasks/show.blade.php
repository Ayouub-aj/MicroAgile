<x-app-layout>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ $task->title }}</h4>
                    <a href="{{ route('tasks.index', $task->project) }}" class="btn btn-sm btn-secondary">
                        ← Retour à la liste
                    </a>
                </div>

                <div class="card-body">
                    <!-- Project -->
                    <div class="mb-3">
                        <strong>Projet:</strong>
                        <a href="{{ route('projects.show', $task->project) }}">{{ $task->project->title }}</a>
                    </div>

                    <!-- Description -->
                    @if($task->description)
                        <div class="mb-3">
                            <strong>Description:</strong>
                            <p class="mt-2">{{ $task->description }}</p>
                        </div>
                    @endif

                    <!-- Status -->
                    <div class="mb-3">
                        <strong>Statut:</strong>
                        <span class="badge bg-{{ $task->status_badge_color }}">
                            {{ $task->status_label }}
                        </span>
                    </div>

                    <!-- Priority -->
                    <div class="mb-3">
                        <strong>Priorité:</strong>
                        <span class="badge bg-{{ $task->priority_badge_color }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>

                    <!-- Assigned User -->
                    <div class="mb-3">
                        <strong>Assigné à:</strong>
                        {{ $task->assignedUser ? $task->assignedUser->name : 'Non assigné' }}
                    </div>

                    <!-- Deadline -->
                    @if($task->deadline)
                        <div class="mb-3">
                            <strong>Deadline:</strong>
                            {{ $task->deadline }}
                            @if($task->deadline_status === 'overdue')
                                <span class="text-danger">🔴 En retard</span>
                            @elseif($task->deadline_status === 'urgent')
                                <span class="text-warning">⚠️ Urgent</span>
                            @endif
                        </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted">
                            Créée le {{ $task->created_at->format('d/m/Y à H:i') }}
                            @if($task->updated_at != $task->created_at)
                                • Modifiée le {{ $task->updated_at->format('d/m/Y à H:i') }}
                            @endif
                        </small>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    @can('update', $task)
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">
                            Éditer
                        </a>
                    @endcan

                    @can('delete', $task)
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                                Supprimer
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
