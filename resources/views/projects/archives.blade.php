<x-app-layout>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Projets Archivés</h1>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary">← Retour aux projets</a>
            </div>

            @if($projects->isEmpty())
                <div class="alert alert-info">
                    Aucun projet archivé.
                </div>
            @else
                <div class="row">
                    @foreach($projects as $project)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-warning">
                                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ $project->title }}</h5>
                                    <span class="badge bg-dark">Archivé</span>
                                </div>

                                <div class="card-body">
                                    @if($project->description)
                                        <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                                    @endif

                                    @if($project->deadline)
                                        <p class="text-muted mb-2">
                                            <strong>Deadline:</strong> {{ $project->deadline }}
                                        </p>
                                    @endif

                                    <div class="mb-3">
                                        <strong>Progression des tâches:</strong>
                                        <div class="progress mt-2">
                                            @php
                                                $percentage = $project->tasks_count > 0
                                                    ? round(($project->done_tasks_count / $project->tasks_count) * 100)
                                                    : 0;
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
                                            {{ $project->done_tasks_count }} / {{ $project->tasks_count }} tâches terminées
                                        </small>
                                    </div>

                                    <small class="text-muted">
                                        Archivé le {{ $project->deleted_at->format('d/m/Y') }}
                                    </small>
                                </div>

                                <div class="card-footer bg-transparent">
                                    @can('restore', $project)
                                        <form action="{{ route('projects.restore', $project) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Restaurer ce projet ?')">
                                                Restaurer
                                            </button>
                                        </form>
                                    @endcan

                                    @can('forceDelete', $project)
                                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Supprimer définitivement ce projet ? Cette action est irréversible !')">
                                                Supprimer définitivement
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
