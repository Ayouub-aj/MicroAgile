 <?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/{project}/tasks', [TaskController::class, 'apiIndex']);
