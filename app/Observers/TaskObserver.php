<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    public function creating(Task $task): void
    {
        $task->refreshPriorityScore();
    }

    public function updating(Task $task): void
    {
        $task->refreshPriorityScore();
    }

    public function saved(Task $task): void
    {
        // Pastikan nilai terbaru disimpan bila ada perubahan di hook lain
        if ($task->isDirty('priority_score')) {
            $task->saveQuietly();
        }
    }
}
