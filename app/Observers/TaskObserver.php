<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskLog;

class TaskObserver
{
    public function creating(Task $task): void
    {
        $task->refreshPriorityScore();
    }

    public function updating(Task $task): void
    {
        // log perubahan field penting
        foreach (['difficulty','desire','obligation','energy','deadline','status'] as $field) {
            if ($task->isDirty($field)) {
                TaskLog::create([
                    'task_id' => $task->id,
                    'field' => $field,
                    'before' => (string) $task->getOriginal($field),
                    'after' => (string) $task->{$field},
                ]);
            }
        }

        // set completed_at saat status menjadi done
        if ($task->isDirty('status') && $task->status === Task::STATUS_DONE && empty($task->completed_at)) {
            $task->completed_at = now();
        }

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
