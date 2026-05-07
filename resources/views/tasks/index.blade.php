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
                    <table class="w-full table-fixed">
                        <colgroup>
                            <col class="w-1/4"> <!-- Titre -->
                            <col class="w-32"> <!-- Statut -->
                            <col class="w-28"> <!-- Priorité -->
                            <col class="w-32"> <!-- Assigné à -->
                            <col class="w-36"> <!-- Date limite -->
                            <col class="w-48"> <!-- Actions -->
                        </colgroup>
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">Titre</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">Statut</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">Priorité</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">Assigné à</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">Date Limite</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($tasks as $task)
                                <tr class="hover:bg-blue-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
                                            {{ $task->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold shadow-sm whitespace-nowrap
                                            @if($task->status === 'todo') bg-gray-200 text-gray-800 border border-gray-300
                                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700 border border-blue-200
                                            @else bg-green-100 text-green-700 border border-green-200
                                            @endif">
                                            @if($task->status === 'todo')
                                                <i class="fas fa-circle text-gray-500 mr-1.5 text-xs"></i>
                                            @elseif($task->status === 'in_progress')
                                                <i class="fas fa-spinner text-blue-600 mr-1.5 text-xs"></i>
                                            @else
                                                <i class="fas fa-check-circle text-green-600 mr-1.5 text-xs"></i>
                                            @endif
                                            {{ $task->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold shadow-sm whitespace-nowrap
                                            @if($task->priority === 'low') bg-blue-50 text-blue-700 border border-blue-200
                                            @elseif($task->priority === 'medium') bg-yellow-50 text-yellow-700 border border-yellow-200
                                            @else bg-red-50 text-red-700 border border-red-200
                                            @endif">
                                            @if($task->priority === 'high')
                                                <i class="fas fa-exclamation-circle mr-1.5"></i>
                                            @endif
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-2">
                                                {{ substr($task->assignedUser?->name ?? 'N', 0, 1) }}
                                            </div>
                                            <span class="text-gray-700 font-medium truncate">{{ $task->assignedUser?->name ?? 'Non assigné' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($task->deadline)
                                            <div class="flex items-center gap-2">
                                                @if($task->deadline_status === 'overdue')
                                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-red-100 text-red-700 border border-red-200 whitespace-nowrap">
                                                        <i class="fas fa-circle text-red-600 mr-1.5 text-xs"></i>
                                                        {{ \Carbon\Carbon::parse($task->deadline)->format('Y-m-d') }}
                                                    </span>
                                                @elseif($task->deadline_status === 'urgent')
                                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200 whitespace-nowrap">
                                                        <i class="fas fa-exclamation-triangle text-orange-600 mr-1.5"></i>
                                                        {{ \Carbon\Carbon::parse($task->deadline)->format('Y-m-d') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-700 font-medium whitespace-nowrap">
                                                        <i class="fas fa-calendar-alt text-gray-400 mr-1.5"></i>
                                                        {{ \Carbon\Carbon::parse($task->deadline)->format('Y-m-d') }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic text-sm">Aucune</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            {{-- Status change form (for assigned developer) --}}
                                            @can('updateStatus', $task)
                                                <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status"
                                                            class="text-xs border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm font-medium px-2 py-1.5"
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
                                                   class="px-3 py-2 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600 transition-colors shadow-sm font-medium">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $task)
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition-colors shadow-sm font-medium"
                                                            onclick="return confirm('Supprimer cette tâche ?')">
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
