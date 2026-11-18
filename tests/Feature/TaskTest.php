<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_shows_only_tasks_of_the_selected_project(): void
    {
        $project = Project::factory()->create();
        $other = Project::factory()->create();
        Task::factory()->for($project)->create(['name' => 'Write report']);
        Task::factory()->for($other)->create(['name' => 'Fix bug']);

        $this->get(route('tasks.index', ['project' => $project->id]))
            ->assertOk()
            ->assertSee('Write report')
            ->assertDontSee('Fix bug');
    }

    public function test_new_task_is_added_at_the_bottom_of_its_project(): void
    {
        $project = Project::factory()->create();
        Task::factory()->for($project)->create(['priority' => 1]);

        $this->post(route('tasks.store'), [
            'project_id' => $project->id,
            'name' => 'New task',
        ])->assertRedirect(route('tasks.index', ['project' => $project->id]));

        $this->assertDatabaseHas('tasks', [
            'name' => 'New task',
            'project_id' => $project->id,
            'priority' => 2,
        ]);
    }

    public function test_task_name_is_required(): void
    {
        $project = Project::factory()->create();

        $this->post(route('tasks.store'), ['project_id' => $project->id, 'name' => ''])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_task_can_be_renamed(): void
    {
        $task = Task::factory()->create(['name' => 'Old name']);

        $this->put(route('tasks.update', $task), ['name' => 'New name'])
            ->assertRedirect(route('tasks.index', ['project' => $task->project_id]));

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'name' => 'New name']);
    }

    public function test_deleting_a_task_closes_the_gap_in_its_project_only(): void
    {
        $project = Project::factory()->create();
        $other = Project::factory()->create();

        $first = Task::factory()->for($project)->create(['priority' => 1]);
        $second = Task::factory()->for($project)->create(['priority' => 2]);
        $third = Task::factory()->for($project)->create(['priority' => 3]);
        $untouched = Task::factory()->for($other)->create(['priority' => 2]);

        $this->delete(route('tasks.destroy', $first))->assertRedirect();

        $this->assertModelMissing($first);
        $this->assertSame(1, $second->fresh()->priority);
        $this->assertSame(2, $third->fresh()->priority);
        $this->assertSame(2, $untouched->fresh()->priority);
    }

    public function test_reordering_updates_priorities(): void
    {
        $project = Project::factory()->create();
        $a = Task::factory()->for($project)->create(['priority' => 1]);
        $b = Task::factory()->for($project)->create(['priority' => 2]);
        $c = Task::factory()->for($project)->create(['priority' => 3]);

        $this->patchJson(route('tasks.reorder'), [
            'project_id' => $project->id,
            'order' => [$c->id, $a->id, $b->id],
        ])->assertNoContent();

        $this->assertSame(1, $c->fresh()->priority);
        $this->assertSame(2, $a->fresh()->priority);
        $this->assertSame(3, $b->fresh()->priority);
    }

    public function test_reordering_rejects_tasks_from_another_project(): void
    {
        $project = Project::factory()->create();
        $foreignTask = Task::factory()->create();

        $this->patchJson(route('tasks.reorder'), [
            'project_id' => $project->id,
            'order' => [$foreignTask->id],
        ])->assertJsonValidationErrors('order.0');
    }
}