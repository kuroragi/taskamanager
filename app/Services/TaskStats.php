<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Carbon;

class TaskStats
{
    public function completedCountLastDays(int $days = 7): int
    {
        return Task::query()
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', now()->subDays($days))
            ->count();
    }

    public function averageDifficultyOfCompleted(int $days = 30): ?float
    {
        return Task::query()
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', now()->subDays($days))
            ->avg('difficulty');
    }

    public function averageCompletionTimeDays(int $days = 30): ?float
    {
        // membutuhkan created_at dan completed_at
        $tasks = Task::query()
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', now()->subDays($days))
            ->get(['created_at','completed_at']);

        if ($tasks->isEmpty()) {
            return null;
        }

        $total = 0.0;
        foreach ($tasks as $t) {
            $total += $t->created_at->diffInDays($t->completed_at);
        }
        return $total / $tasks->count();
    }
}
