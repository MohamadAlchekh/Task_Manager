<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;   
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReorderTasksRequest;
use Illuminate\Http\Response;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        $project = $projects->firstWhere('id', $request->integer('project')) ?? $projects->first();

        return view('tasks.index', [
            'projects' => $projects,
            'project' => $project,
            'tasks' => $project ? $project->tasks()->orderBy('priority')->get() : collect(),
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $project = Project::findOrFail($request->validated('project_id'));

        $project->tasks()->create([
            'name' => $request->validated('name'),
            'priority' => ($project->tasks()->max('priority') ?? 0) + 1,
        ]);

        return redirect()->route('tasks.index', ['project' => $project->id]);
    }
  
    public function edit(Task $task): View
    {    
        return view('tasks.edit', ['task' => $task]);
    }

    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index', ['project' => $task->project_id]);
    }

    public function destroy(Task $task): RedirectResponse
    {
        DB::transaction(function () use ($task) {
        $task->delete();

        Task::where('project_id', $task->project_id)
            ->where('priority', '>', $task->priority)
            ->decrement('priority');
        });

        return redirect()->route('tasks.index', ['project' => $task->project_id]);
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