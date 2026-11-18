<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

    Route::redirect('/', '/tasks');
    Route::patch('tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
    Route::resource('tasks', TaskController::class)->except(['create', 'show']);