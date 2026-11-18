<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (['Website redesign', 'Mobile app'] as $name) {
            $project = Project::create(['name' => $name]);

            foreach (['First task', 'Second task', 'Third task'] as $position => $taskName) {
                $project->tasks()->create([
                    'name' => $taskName,
                    'priority' => $position + 1,
                ]);
            }
        }
    }
}
