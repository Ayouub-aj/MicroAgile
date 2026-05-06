<x-app-layout>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Tasks for {{ $project->title }}</h4>
                    @can('create', [App\Models\Task::class, $project])
                        <a href="{{ route('tasks.create', $project) }}" class="btn btn-primary">New Task</a>
                    @endcan
                </div>

                <div class="card-body">
                    @if($tasks->isEmpty())
                        <p class="text-muted">No tasks yet.</p>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Assigned To</th>
                                    <th>Deadline</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $task->status_badge_color }}">
                                                {{ $task->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $task->priority_badge_color }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $task->assignedUser ? $task->assignedUser->name : 'Unassigned' }}
                                        </td>
                                        <td>
                                            @if($task->deadline)
                                                {{ $task->deadline }}
                                                @if($task->deadline_status === 'overdue')
                                                    <span class="text-danger">🔴</span>
                                                @elseif($task->deadline_status === 'urgent')
                                                    <span class="text-warning">⚠️</span>
                                                @endif
                                            @else
                                                <span class="text-muted">No deadline</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- Status change form (for assigned developer) --}}
                                            @can('updateStatus', $task)
                                                <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                                        <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>À faire</option>
                                                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                                                        <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Terminé</option>
                                                    </select>
                                                </form>
                                            @endcan

                                            {{-- Edit/Delete for leads only --}}
                                            @can('update', $task)
                                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-warning">Edit</a>
                                            @endcan

                                            @can('delete', $task)
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
