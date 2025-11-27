<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Services\PriorityEngine;
use Livewire\Component;

class KanbanBoard extends Component
{
    public array $columns = [
        Task::STATUS_TODO,
        Task::STATUS_DOING,
        Task::STATUS_DONE,
        Task::STATUS_HOLD,
    ];

    public ?string $search = null;
    public ?string $tag = null; // placeholder untuk fase opsional
    public ?string $deadlineFilter = null; // 'overdue' | 'soon' | null
    public ?int $energyMin = null; // optional filter

    public function move(int $taskId, string $toStatus, PriorityEngine $engine): void
    {
        if (!in_array($toStatus, $this->columns, true)) {
            return;
        }

        $task = Task::findOrFail($taskId);
        $task->status = $toStatus;
        $engine->recalculate($task);
        $task->save();
        $this->dispatch('task-moved', id: $taskId, status: $toStatus);
    }

    public function getTasksByStatus(string $status)
    {
        $query = Task::query()->where('status', $status);

        if ($this->search) {
            $q = '%'.$this->search.'%';
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', $q)
                  ->orWhere('description', 'like', $q);
            });
        }

        if ($this->deadlineFilter === 'overdue') {
            $query->whereNotNull('deadline')->where('deadline', '<', now());
        } elseif ($this->deadlineFilter === 'soon') {
            $query->whereNotNull('deadline')->whereBetween('deadline', [now(), now()->addDays(3)]);
        }

        if (!is_null($this->energyMin)) {
            $query->whereNotNull('energy')->where('energy', '>=', $this->energyMin);
        }

        return $query->orderByPriority()->get();
    }

    public function render()
    {
        return view('livewire.tasks.kanban-board');
    }
}
