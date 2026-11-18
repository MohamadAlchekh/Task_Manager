<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_can_be_created(): void
    {
        $this->post(route('projects.store'), ['project_name' => 'Launch'])
            ->assertRedirect();

        $this->assertDatabaseHas('projects', ['name' => 'Launch']);
    }

    public function test_project_name_must_be_unique(): void
    {
        Project::factory()->create(['name' => 'Launch']);

        $this->post(route('projects.store'), ['project_name' => 'Launch'])
            ->assertSessionHasErrors('project_name');

        $this->assertDatabaseCount('projects', 1);
    }
}
