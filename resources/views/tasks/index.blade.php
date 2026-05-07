<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('projects.show', $project) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-tasks mr-2 text-blue-600"></i>
                    Tâches pour {{ $project->title }}
                </h2>
            </div>
            @can('create', [App\Models\Task::class, $project])
                <a href="{{ route('tasks.create', $project) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                    <i class="fas fa-plus mr-2"></i>Nouvelle tâche
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @if($tasks->isEmpty())
                <div class="p-12 text-center">
                    <i class="fas fa-tasks text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Aucune tâche pour le moment.</p>
                    @can('create', [App\Models\Task::class, $project])
                        <a href="{{ route('tasks.create', $project) }}"
                           class="inline-block mt-4 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            <i class="fas fa-plus mr-2"></i>Créer une tâche
                        </a>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Titre</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Priorité</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assigné à</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date limite</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($tasks as $task)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                            {{ $task->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                            @if($task->status === 'todo') bg-gray-100 text-gray-800
                                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ $task->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                            @if($task->priority === 'low') bg-gray-100 text-gray-800
                                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $task->assignedUser ? $task->assignedUser->name : 'Non assigné' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        @if($task->deadline)
                                            {{ $task->deadline }}
                                            @if($task->deadline_status === 'overdue')
                                                <span class="text-red-600 ml-1">🔴</span>
                                            @elseif($task->deadline_status === 'urgent')
                                                <span class="text-yellow-600 ml-1">⚠️</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">Pas de date limite</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            {{-- Status change form (for assigned developer) --}}
                                            @can('updateStatus', $task)
                                                <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status"
                                                            class="text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                            onchange="this.form.submit()">
                                                        <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>À faire</option>
                                                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                                                        <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Terminé</option>
                                                    </select>
                                                </form>
                                            @endcan

                                            {{-- Edit/Delete for leads only --}}
                                            @can('update', $task)
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                   class="px-3 py-1.5 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600 transition">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $task)
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1.5 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition"
                                                            onclick="return confirm('Êtes-vous sûr ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
