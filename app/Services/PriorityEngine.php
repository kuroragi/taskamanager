<?php

namespace App\Services;

use App\Models\Task;

class PriorityEngine
{
    /**
     * Recalculate priority for a single task instance (in-memory).
     */
    public function recalculate(Task $task): void
    {
        $task->refreshPriorityScore();
    }

    /**
     * Recalculate all tasks and persist changes.
     */
    public function recalculateAll(): int
    {
        $count = 0;
        Task::query()->chunk(500, function ($tasks) use (&$count) {
            foreach ($tasks as $task) {
                $task->refreshPriorityScore();
                $task->saveQuietly();
                $count++;
            }
        });

        return $count;
    }
}
