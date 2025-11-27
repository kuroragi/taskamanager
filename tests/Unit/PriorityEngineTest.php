<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Services\PriorityEngine;
use Tests\TestCase;

class PriorityEngineTest extends TestCase
{
    public function test_bulk_recalculate_updates_all_tasks(): void
    {
        Task::factory()->count(5)->create([
            'difficulty' => 3,
            'desire' => 8,
            'obligation' => 5,
            'deadline' => now()->addDays(1),
            'status' => Task::STATUS_TODO,
            'priority_score' => 0,
        ]);

        $engine = new PriorityEngine();
        $updated = $engine->recalculateAll();

        $this->assertSame(5, $updated);
        $this->assertGreaterThan(0, Task::first()->priority_score);
    }

    public function test_scope_order_by_priority_desc(): void
    {
        Task::factory()->create(['difficulty' => 10, 'desire' => 1, 'obligation' => 1, 'deadline' => null, 'status' => Task::STATUS_TODO]);
        Task::factory()->create(['difficulty' => 1, 'desire' => 9, 'obligation' => 9, 'deadline' => now()->addDay(), 'status' => Task::STATUS_TODO]);

        (new PriorityEngine())->recalculateAll();

        $tasks = Task::query()->orderByPriority()->get();
        $this->assertTrue($tasks->first()->priority_score >= $tasks->last()->priority_score);
    }

    public function test_completed_at_is_set_when_status_done(): void
    {
        $task = Task::factory()->create(['status' => Task::STATUS_TODO]);
        $this->assertNull($task->completed_at);
        $task->status = Task::STATUS_DONE;
        $task->save();
        $this->assertNotNull($task->fresh()->completed_at);
    }
}
