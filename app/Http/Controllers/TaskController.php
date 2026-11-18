<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;   
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReorderTasksRequest;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(): View
    {
        return view('tasks.index', [
            'tasks' => Task::orderBy('priority')->get(),
        ]);
    }

    public function store(TaskRequest $request): RedirectResponse
    {
        Task::create([
            'name' => $request->validated('name'),
            'priority' => (Task::max('priority') ?? 0) + 1,
        ]);

        return redirect()->route('tasks.index');
    }
  
    public function edit(Task $task): View
    {    
        return view('tasks.edit', ['task' => $task]);
    }

    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task): RedirectResponse
    {
        DB::transaction(function () use ($task) {
          $task->delete();

        Task::where('priority', '>', $task->priority)->decrement('priority');
    });

        return redirect()->route('tasks.index');
    }
    public function reorder(ReorderTasksRequest $request): Response
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('order') as $position => $id) {
                Task::whereKey($id)->update(['priority' => $position + 1]);
            }
        });

        return response()->noContent();
    }
}