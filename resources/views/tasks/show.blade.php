<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('tasks.index', $task->project) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-tasks mr-2 text-blue-600"></i>
                    {{ $task->title }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-8">
                <!-- Project -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Projet</span>
                    <p class="mt-2">
                        <a href="{{ route('projects.show', $task->project) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                            {{ $task->project->title }}
                        </a>
                    </p>
                </div>

                <!-- Description -->
                @if($task->description)
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Description</span>
                        <p class="mt-2 text-gray-700 leading-relaxed">{{ $task->description }}</p>
                    </div>
                @endif

                <div class="grid md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200">
                    <!-- Status -->
                    <div>
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider block mb-2">Statut</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($task->status === 'todo') bg-gray-100 text-gray-800
                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ $task->status_label }}
                        </span>
                    </div>

                    <!-- Priority -->
                    <div>
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider block mb-2">Priorité</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($task->priority === 'low') bg-gray-100 text-gray-800
                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>

                    <!-- Assigned User -->
                    <div>
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider block mb-2">Assigné à</span>
                        <p class="text-gray-700">{{ $task->assignedUser ? $task->assignedUser->name : 'Non assigné' }}</p>
                    </div>

                    <!-- Deadline -->
                    @if($task->deadline)
                        <div>
                            <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider block mb-2">Date limite</span>
                            <p class="text-gray-700">
                                {{ $task->deadline }}
                                @if($task->deadline_status === 'overdue')
                                    <span class="text-red-600 ml-2">🔴 En retard</span>
                                @elseif($task->deadline_status === 'urgent')
                                    <span class="text-yellow-600 ml-2">⚠️ Urgent</span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Timestamps -->
                <div class="text-sm text-gray-500">
                    Créée le {{ $task->created_at->format('d/m/Y à H:i') }}
                    @if($task->updated_at != $task->created_at)
                        • Modifiée le {{ $task->updated_at->format('d/m/Y à H:i') }}
                    @endif
                </div>
            </div>

            <!-- Actions Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex items-center justify-between rounded-b-xl">
                <div class="space-x-3">
                    @can('update', $task)
                        <a href="{{ route('tasks.edit', $task) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            <i class="fas fa-edit mr-2"></i>Éditer
                        </a>
                    @endcan

                    @can('delete', $task)
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                                <i class="fas fa-trash mr-2"></i>Supprimer
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
