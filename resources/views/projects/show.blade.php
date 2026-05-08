<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-folder-open mr-2 text-blue-600"></i>
                    {{ $project->title }}
                </h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('tasks.index', $project) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                    <i class="fas fa-tasks mr-2"></i>Voir les tâches
                </a>
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}"
                       class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium shadow-sm">
                        <i class="fas fa-edit mr-2"></i>Éditer
                    </a>
                @endcan
                @can('delete', $project)
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium shadow-sm"
                                onclick="confirmDelete(this.closest('form'), 'Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.')">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Project Details Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-8">
                @if($project->description)
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Description</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $project->description }}</p>
                    </div>
                @endif

                @if($project->deadline)
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Date limite</h3>
                        <p class="text-gray-700">{{ $project->deadline }}</p>
                    </div>
                @endif

                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Progression des tâches</h3>
                    @php
                        $tasksCount = $project->tasks->count();
                        $doneTasks = $project->tasks->where('status', 'done')->count();
                        $percentage = $tasksCount > 0 ? round(($doneTasks / $tasksCount) * 100) : 0;
                    @endphp
                    <div class="relative">
                        <div class="overflow-hidden h-4 text-xs flex rounded-lg bg-gray-200">
                            <div style="width: {{ $percentage }}%;"
                                 class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-500">
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm font-semibold text-gray-700">{{ $percentage }}%</span>
                            <span class="text-sm text-gray-500">{{ $doneTasks }} / {{ $tasksCount }} tâches terminées</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Tasks List Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-8 py-6 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-tasks mr-2 text-blue-600"></i>
                        Tâches du Projet
                    </h3>
                    @can('create', [App\Models\Task::class, $project])
                        <a href="{{ route('tasks.create', $project) }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm text-sm">
                            <i class="fas fa-plus mr-2"></i>Nouvelle Tâche
                        </a>
                    @endcan
                </div>

                <div class="p-8">
                    @if($project->tasks->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-tasks text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Aucune tâche pour le moment.</p>
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
                                    <col class="w-40"> <!-- Actions -->
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
                                    @foreach($project->tasks as $task)
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
                                                    <a href="{{ route('tasks.edit', $task) }}"
                                                       class="px-3 py-2 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600 transition-colors shadow-sm font-medium">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                onclick="confirmDelete(this.closest('form'), 'Supprimer cette tâche ?')"
                                                                class="px-3 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition-colors shadow-sm font-medium">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('tasks.index', $project) }}"
                               class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition font-medium">
                                <i class="fas fa-list mr-2"></i>Voir toutes les tâches
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Members Management Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-8 py-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-users mr-2 text-blue-600"></i>
                        Membres du Projet
                    </h3>
                </div>

                <div class="p-8">
                    <!-- Members List -->
                    <div class="overflow-x-auto mb-8">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rôle</th>
                                    @can('update', $project)
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($project->members as $member)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-4 text-gray-900 font-medium">{{ $member->name }}</td>
                                        <td class="px-4 py-4 text-gray-700">{{ $member->email }}</td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                {{ $member->pivot->role === 'lead' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $member->pivot->role === 'lead' ? 'Lead' : 'Developer' }}
                                            </span>
                                        </td>
                                        @can('update', $project)
                                            <td class="px-4 py-4">
                                                @if($member->id !== auth()->id())
                                                    <form action="{{ route('projects.members.remove', [$project, $member]) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                class="px-3 py-1.5 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition"
                                                                onclick="confirmDelete(this.closest('form'), 'Retirer {{ $member->name }} du projet ?')">
                                                            <i class="fas fa-user-minus mr-1"></i>Retirer
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-500 text-sm italic">Vous</span>
                                                @endif
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Member Form -->
                    @can('update', $project)
                        <div class="pt-8 border-t border-gray-200">
                            <h4 class="text-md font-semibold text-gray-700 mb-4">Ajouter un Membre</h4>

                            @if($availableUsers->isEmpty())
                                <p class="text-gray-500 text-sm italic">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Tous les utilisateurs sont déjà membres de ce projet.
                                </p>
                            @else
                                <form action="{{ route('projects.members.add', $project) }}" method="POST"
                                      x-data="{
                                          search: '',
                                          selectedUserId: '',
                                          selectedUserName: '',
                                          showDropdown: false,
                                          users: {{ $availableUsers->toJson() }},
                                          get filteredUsers() {
                                              if (this.search === '') return this.users;
                                              return this.users.filter(user =>
                                                  user.name.toLowerCase().includes(this.search.toLowerCase()) ||
                                                  user.email.toLowerCase().includes(this.search.toLowerCase())
                                              );
                                          },
                                          selectUser(user) {
                                              this.selectedUserId = user.id;
                                              this.selectedUserName = user.name;
                                              this.search = user.name + ' (' + user.email + ')';
                                              this.showDropdown = false;
                                          },
                                          reset() {
                                              this.search = '';
                                              this.selectedUserId = '';
                                              this.selectedUserName = '';
                                              this.showDropdown = false;
                                          }
                                      }"
                                      class="flex gap-3"
                                      @click.away="showDropdown = false">
                                    @csrf

                                    <div class="flex-1 relative">
                                        <!-- Hidden input for user_id -->
                                        <input type="hidden" name="user_id" x-model="selectedUserId">

                                        <!-- Search Input -->
                                        <input type="text"
                                               x-model="search"
                                               @focus="showDropdown = true"
                                               @input="showDropdown = true; selectedUserId = ''"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('user_id') border-red-500 @enderror"
                                               placeholder="Rechercher par nom ou email..."
                                               autocomplete="off">

                                        <!-- Dropdown List -->
                                        <div x-show="showDropdown && filteredUsers.length > 0"
                                             x-transition
                                             class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                            <template x-for="user in filteredUsers" :key="user.id">
                                                <div @click="selectUser(user)"
                                                     class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition border-b border-gray-100 last:border-0">
                                                    <div class="font-medium text-gray-800" x-text="user.name"></div>
                                                    <div class="text-sm text-gray-500" x-text="user.email"></div>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- No results message -->
                                        <div x-show="showDropdown && search !== '' && filteredUsers.length === 0"
                                             x-transition
                                             class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg p-4">
                                            <p class="text-gray-500 text-sm">
                                                <i class="fas fa-search mr-2"></i>Aucun utilisateur trouvé
                                            </p>
                                        </div>

                                        @error('user_id')
                                            <p class="mt-2 text-sm text-red-600">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                            :disabled="!selectedUserId"
                                            :class="selectedUserId ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed'"
                                            class="px-6 py-3 text-white rounded-lg transition font-medium shadow-sm">
                                        <i class="fas fa-user-plus mr-2"></i>Ajouter
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
