<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-archive mr-2 text-yellow-600"></i>
                Projets Archivés
            </h2>
            <a href="{{ route('projects.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-sm transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour aux projets
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($projects->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="max-w-md mx-auto">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun projet archivé</h3>
                <p class="text-gray-500 mb-6">Les projets archivés apparaîtront ici.</p>
                <a href="{{ route('projects.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour aux projets
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
                <div class="bg-white rounded-xl shadow-sm border-2 border-yellow-300 hover:shadow-md transition-all duration-300 overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-yellow-50 border-b border-yellow-200 p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-gray-800">
                                {{ $project->title }}
                            </h3>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-archive mr-1"></i>Archivé
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5">
                        @if($project->description)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ Str::limit($project->description, 100) }}
                            </p>
                        @endif

                        @if($project->deadline)
                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between text-xs text-gray-600 mb-2">
                                <span class="font-semibold">Progression</span>
                                @php
                                    $percentage = $project->tasks_count > 0
                                        ? round(($project->done_tasks_count / $project->tasks_count) * 100)
                                        : 0;
                                @endphp
                                <span class="font-bold text-yellow-600">{{ $percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full transition-all duration-500"
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $project->done_tasks_count }} / {{ $project->tasks_count }} tâches terminées
                            </p>
                        </div>

                        <div class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            Archivé le {{ $project->deleted_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="bg-gray-50 border-t border-gray-200 px-5 py-3 flex justify-between items-center">
                        @can('restore', $project)
                            <form action="{{ route('projects.restore', $project) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="button"
                                        onclick="confirmDelete(this.closest('form'), 'Restaurer ce projet ?')"
                                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition">
                                    <i class="fas fa-undo mr-2"></i>
                                    Restaurer
                                </button>
                            </form>
                        @endcan

                        @can('forceDelete', $project)
                            <form action="{{ route('projects.force-delete', $project) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        onclick="confirmDelete(this.closest('form'), 'Supprimer définitivement ce projet ? Toutes les données seront perdues. Cette action est irréversible !', true)"
                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition">
                                    <i class="fas fa-trash mr-2"></i>
                                    Supprimer
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
