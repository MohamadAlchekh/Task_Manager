<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/tasks');
Route::patch('tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
Route::resource('tasks', TaskController::class)->except(['create', 'show']);
Route::resource('projects', ProjectController::class)->only('store');
