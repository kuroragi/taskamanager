<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;

class PriorityList extends Component
{
    public ?string $search = null;
    public ?string $deadlineFilter = null; // 'overdue' | 'soon' | null
    public ?int $energyMin = null;
    public string $mode = 'priority'; // 'priority' | 'deadline' | 'easiest'

    public function list()
    {
        $query = Task::query();
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

    public function nearestDeadline()
    {
        return Task::query()
            ->whereNotNull('deadline')
            ->orderBy('deadline')
            ->get();
    }

    public function easiest()
    {
        return Task::query()->orderBy('difficulty')->get();
    }

    public function render()
    {
        return view('livewire.tasks.priority-list');
    }
}
