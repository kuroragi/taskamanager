<?php

namespace App\Console\Commands;

use App\Services\PriorityEngine;
use Illuminate\Console\Command;

class RecalculateTaskPriorities extends Command
{
    protected $signature = 'tasks:recalculate-priority';
    protected $description = 'Recalculate priority scores for all tasks';

    public function handle(PriorityEngine $engine): int
    {
        $this->info('Starting priority recalculation...');
        $count = $engine->recalculateAll();
        $this->info("Recalculation complete. Updated {$count} tasks.");
        return self::SUCCESS;
    }
}
