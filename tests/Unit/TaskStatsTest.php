<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Services\TaskStats;
use Tests\TestCase;

class TaskStatsTest extends TestCase
{
    public function test_stats_basic_metrics(): void
    {
        Task::factory()->create(['status' => Task::STATUS_DONE, 'completed_at' => now()->subDays(1), 'difficulty' => 5]);
        Task::factory()->create(['status' => Task::STATUS_DONE, 'completed_at' => now()->subDays(2), 'difficulty' => 7]);
        Task::factory()->create(['status' => Task::STATUS_TODO]);

        $stats = new TaskStats();
        $count7 = $stats->completedCountLastDays(7);
        $avgDiff = $stats->averageDifficultyOfCompleted(7);
        $avgDays = $stats->averageCompletionTimeDays(30);

        $this->assertSame(2, $count7);
        $this->assertNotNull($avgDiff);
        $this->assertIsFloat($avgDiff);
        $this->assertIsFloat($avgDays);
    }
}
