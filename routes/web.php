<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

// Task routes
Route::get('projects/{project}/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('projects/{project}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
