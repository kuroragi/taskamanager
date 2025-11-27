<?php

namespace Tests\Feature;

use App\Livewire\Tasks\TaskManager;
use App\Models\Task;
use Livewire\Livewire;
use Tests\TestCase;

class LivewireTaskManagerTest extends TestCase
{
    public function test_can_create_task_and_calculates_priority(): void
    {
        Livewire::test(TaskManager::class)
            ->set('form.title', 'Tes Buat Task')
            ->set('form.difficulty', 4)
            ->set('form.desire', 7)
            ->set('form.obligation', 6)
            ->call('create');

        $this->assertDatabaseCount('tasks', 1);
        $task = Task::first();
        $this->assertGreaterThanOrEqual(0, $task->priority_score);
    }

    public function test_update_task_recalculates_priority(): void
    {
        $task = Task::factory()->create([
            'difficulty' => 8,
            'desire' => 2,
            'obligation' => 3,
            'deadline' => null,
            'status' => Task::STATUS_TODO,
        ]);

        $oldScore = $task->priority_score;

        Livewire::test(TaskManager::class)
            ->call('edit', $task->id)
            ->set('form.difficulty', 2)
            ->set('form.desire', 9)
            ->set('form.obligation', 9)
            ->call('update');

        $task->refresh();
        $this->assertNotSame($oldScore, $task->priority_score);
    }

    public function test_delete_task(): void
    {
        $task = Task::factory()->create();
        Livewire::test(TaskManager::class)
            ->call('delete', $task->id);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_archive_and_restore_task(): void
    {
        $task = Task::factory()->create();

        Livewire::test(TaskManager::class)
            ->call('archive', $task->id);

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);

        Livewire::test(TaskManager::class)
            ->call('restore', $task->id);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'deleted_at' => null]);
    }

    public function test_update_status_recalculates_priority(): void
    {
        $task = Task::factory()->create(['status' => Task::STATUS_TODO]);
        $old = $task->priority_score;

        Livewire::test(TaskManager::class)
            ->call('updateStatus', $task->id, Task::STATUS_DOING);

        $task->refresh();
        $this->assertSame(Task::STATUS_DOING, $task->status);
        $this->assertNotSame($old, $task->priority_score);
    }
}
