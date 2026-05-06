@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Modifier la tâche</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $task->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Priority --}}
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priorité <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror"
                                    id="priority"
                                    name="priority"
                                    required>
                                <option value="">-- Choisir une priorité --</option>
                                <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Basse</option>
                                <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>Haute</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Assigned Developer --}}
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Assigner à</label>
                            <select class="form-select @error('user_id') is-invalid @enderror"
                                    id="user_id"
                                    name="user_id">
                                <option value="">-- Non assigné --</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('user_id', $task->user_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deadline --}}
                        <div class="mb-3">
                            <label for="deadline" class="form-label">Date limite</label>
                            <input type="date"
                                   class="form-control @error('deadline') is-invalid @enderror"
                                   id="deadline"
                                   name="deadline"
                                   value="{{ old('deadline', $task->deadline) }}"
                                   min="{{ date('Y-m-d') }}">
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tasks.index', $project) }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
