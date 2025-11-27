<?php

namespace Tests\Unit;

use App\Models\Task;
use Carbon\Carbon;
use Tests\TestCase;

class TaskPriorityTest extends TestCase
{
    public function test_priority_calculation_with_deadline_and_values(): void
    {
        $task = new Task([
            'title' => 'Uji Prioritas',
            'difficulty' => 4,
            'desire' => 7,
            'obligation' => 6,
            'deadline' => Carbon::now()->addDays(2),
            'status' => Task::STATUS_TODO,
        ]);

        $task->refreshPriorityScore();

        $urgency = $task->calculateUrgency();
        $expected = ($urgency * 3) + (6 * 2) + 7 - 4;

        $this->assertSame($expected, $task->priority_score);
    }

    public function test_urgency_zero_when_deadline_null(): void
    {
        $task = new Task([
            'title' => 'Tanpa Deadline',
            'difficulty' => 5,
            'desire' => 5,
            'obligation' => 5,
            'deadline' => null,
            'status' => Task::STATUS_TODO,
        ]);

        $this->assertSame(0, $task->calculateUrgency());
    }

    public function test_observer_sets_priority_on_create(): void
    {
        $task = Task::create([
            'title' => 'Observer Create',
            'difficulty' => 3,
            'desire' => 8,
            'obligation' => 5,
            'deadline' => Carbon::now()->addDays(1),
            'status' => Task::STATUS_TODO,
        ]);

        $this->assertIsFloat($task->priority_score);
        $this->assertGreaterThanOrEqual(0, $task->priority_score);
    }
}
