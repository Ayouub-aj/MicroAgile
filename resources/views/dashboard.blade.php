<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-home mr-2 text-blue-600"></i>
            Dashboard
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold mb-2">Bienvenue, {{ Auth::user()->name }} ! 👋</h3>
                    <p class="text-blue-100">Voici un aperçu de vos projets et tâches</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-chart-line text-6xl text-blue-300 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @php
                $totalProjects = Auth::user()->projects()->count();
                $totalTasks = Auth::user()->tasks()->count();
                $completedTasks = Auth::user()->tasks()->where('status', 'done')->count();
                $urgentTasks = Auth::user()->tasks()->where('status', '!=', 'done')
                    ->where('deadline', '<=', now()->addHours(48))->count();
            @endphp

            <!-- Total Projects -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Projets</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalProjects }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-folder text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Tasks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Tâches totales</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalTasks }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tasks text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Terminées</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $completedTasks }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Urgent Tasks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Urgentes</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ $urgentTasks }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('projects.index') }}"
               class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-blue-300 transition group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                        <i class="fas fa-folder-open text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 group-hover:text-blue-600 transition">Voir tous mes projets</h4>
                        <p class="text-sm text-gray-500">Gérer vos projets</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('projects.create') }}"
               class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-green-300 transition group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                        <i class="fas fa-plus-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 group-hover:text-green-600 transition">Nouveau projet</h4>
                        <p class="text-sm text-gray-500">Créer un projet</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-purple-300 transition group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition">
                        <i class="fas fa-user-cog text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 group-hover:text-purple-600 transition">Mon profil</h4>
                        <p class="text-sm text-gray-500">Paramètres du compte</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Projects -->
        @php
            $recentProjects = Auth::user()->projects()
                ->withCount([
                    'tasks',
                    'tasks as done_tasks_count' => fn($q) => $q->where('status', 'done'),
                    'tasks as urgent_tasks_count' => fn($q) => $q->urgent()
                ])
                ->latest()
                ->take(3)
                ->get();
        @endphp

        @if($recentProjects->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                        Projets récents
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($recentProjects as $project)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-semibold text-gray-800">{{ $project->title }}</h4>
                                        @if($project->urgent_tasks_count > 0)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600 flex items-center gap-1">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ $project->urgent_tasks_count }} urgente{{ $project->urgent_tasks_count > 1 ? 's' : '' }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $project->done_tasks_count }} / {{ $project->tasks_count }} tâches terminées
                                    </p>
                                </div>
                                <a href="{{ route('projects.show', $project) }}"
                                   class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition">
                                    Voir
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
