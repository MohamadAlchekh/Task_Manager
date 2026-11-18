<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        return view('tasks.index', [
            'tasks' => Task::orderBy('priority')->get(),
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        Task::create([
            'name' => $request->validated('name'),
            'priority' => (Task::max('priority') ?? 0) + 1,
        ]);

        return redirect()->route('tasks.index');
    }
}