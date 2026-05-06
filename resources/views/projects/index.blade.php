<x-app-layout>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Mes Projets</h1>
                @can('create', App\Models\Project::class)
                    <a href="{{ route('projects.create') }}" class="btn btn-primary">Nouveau Projet</a>
                @endcan
            </div>

            @if($projects->isEmpty())
                <div class="alert alert-info">
                    Vous n'êtes membre d'aucun projet pour le moment.
                </div>
            @else
                <div class="row">
                    @foreach($projects as $project)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ $project->title }}</h5>
                                    @php
                                        $userRole = $project->members->where('id', auth()->id())->first()?->pivot->role;
                                    @endphp
                                    <span class="badge bg-{{ $userRole === 'lead' ? 'primary' : 'secondary' }}">
                                        {{ $userRole === 'lead' ? 'Lead' : 'Developer' }}
                                    </span>
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
                                </div>

                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-info">Voir</a>
                                    <a href="{{ route('tasks.index', $project) }}" class="btn btn-sm btn-secondary">Tâches</a>

                                    @can('update', $project)
                                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-warning">Éditer</a>
                                    @endcan

                                    @can('delete', $project)
                                        <form action="{{ route('projects.archive', $project) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir archiver ce projet ?')">
                                                Archiver
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
