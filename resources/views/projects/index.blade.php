<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-folder mr-2 text-blue-600"></i>
                Mes Projets
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('projects.archives') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-sm transition">
                    <i class="fas fa-archive mr-2"></i>
                    Archives
                </a>
                @can('create', App\Models\Project::class)
                    <a href="{{ route('projects.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-sm transition">
                        <i class="fas fa-plus mr-2"></i>
                        Nouveau Projet
                    </a>
                @endcan
            </div>
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
                <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun projet</h3>
                <p class="text-gray-500 mb-6">Vous n'êtes membre d'aucun projet pour le moment.</p>
                @can('create', App\Models\Project::class)
                    <a href="{{ route('projects.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-sm transition">
                        <i class="fas fa-plus mr-2"></i>
                        Créer votre premier projet
                    </a>
                @endcan
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 overflow-hidden group">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition">
                                {{ $project->title }}
                            </h3>
                            @php
                                $userRole = $project->members->where('id', auth()->id())->first()?->pivot->role;
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $userRole === 'lead' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $userRole === 'lead' ? 'Lead' : 'Dev' }}
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
                                <span class="font-bold text-blue-600">{{ $percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-500"
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $project->done_tasks_count }} / {{ $project->tasks_count }} tâches terminées
                            </p>
                        </div>

                        <!-- Team Members -->
                        <div class="flex items-center mb-4">
                            <i class="fas fa-users text-gray-400 mr-2 text-sm"></i>
                            <div class="flex -space-x-2">
                                @foreach($project->members->take(3) as $member)
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white"
                                         title="{{ $member->name }}">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endforeach
                                @if($project->members->count() > 3)
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-700 text-xs font-bold border-2 border-white">
                                        +{{ $project->members->count() - 3 }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="bg-gray-50 border-t border-gray-200 px-5 py-3 flex justify-between items-center">
                        <div class="flex space-x-2">
                            <a href="{{ route('projects.show', $project) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition">
                                <i class="fas fa-eye mr-1.5"></i>
                                Voir
                            </a>
                            <a href="{{ route('tasks.index', $project) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                                <i class="fas fa-tasks mr-1.5"></i>
                                Tâches
                            </a>
                        </div>

                        @can('update', $project)
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="text-gray-600 hover:text-blue-600 transition p-2">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>

                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 bottom-full mb-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                                     style="display: none;">

                                    <a href="{{ route('projects.edit', $project) }}"
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fas fa-edit w-5 mr-2 text-yellow-500"></i>
                                        Éditer
                                    </a>

                                    <form action="{{ route('projects.archive', $project) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition text-left">
                                            <i class="fas fa-archive w-5 mr-2 text-gray-500"></i>
                                            Archiver
                                        </button>
                                    </form>

                                    @can('delete', $project)
                                        <div class="border-t border-gray-200 my-1"></div>
                                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition text-left"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.')">
                                                <i class="fas fa-trash w-5 mr-2"></i>
                                                Supprimer
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
