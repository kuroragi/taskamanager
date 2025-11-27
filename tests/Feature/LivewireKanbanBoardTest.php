<?php

namespace Tests\Feature;

use App\Livewire\Tasks\KanbanBoard;
use App\Models\Task;
use Livewire\Livewire;
use Tests\TestCase;

class LivewireKanbanBoardTest extends TestCase
{
    public function test_move_task_changes_status_and_recalculates(): void
    {
        $task = Task::factory()->create([
            'status' => Task::STATUS_TODO,
            'difficulty' => 5,
            'desire' => 5,
            'obligation' => 5,
        ]);
        $oldScore = $task->priority_score;

        Livewire::test(KanbanBoard::class)
            ->call('move', $task->id, Task::STATUS_DOING);

        $task->refresh();
        $this->assertSame(Task::STATUS_DOING, $task->status);
        $this->assertNotNull($task->priority_score);
        $this->assertGreaterThanOrEqual(0, $task->priority_score);
    }

    public function test_search_and_deadline_filters_work(): void
    {
        Task::factory()->create(['title' => 'Belajar Laravel', 'status' => Task::STATUS_TODO, 'deadline' => now()->addDays(1)]);
        Task::factory()->create(['title' => 'Main Game', 'status' => Task::STATUS_TODO, 'deadline' => now()->addDays(10)]);
        Task::factory()->create(['title' => 'Tugas Kemarin', 'status' => Task::STATUS_TODO, 'deadline' => now()->subDay()]);

        $component = Livewire::test(KanbanBoard::class)
            ->set('search', 'Belajar')
            ->set('deadlineFilter', 'soon');

        // panggil render agar state konsisten; verifikasi via method langsung
        $component->call('render');

        $instance = app(\App\Livewire\Tasks\KanbanBoard::class);
        $filtered = $instance->getTasksByStatus(Task::STATUS_TODO);

        $this->assertTrue($filtered->contains(function ($t) {
            return str_contains($t->title, 'Belajar');
        }));
        $this->assertTrue($filtered->every(function ($t) {
            return $t->deadline && $t->deadline->between(now(), now()->addDays(3));
        }));
    }
}
