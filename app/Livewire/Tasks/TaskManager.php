<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Services\PriorityEngine;
use Livewire\Component;

class TaskManager extends Component
{
    public array $form = [
        'title' => '',
        'description' => null,
        'difficulty' => 5,
        'desire' => 5,
        'obligation' => 5,
        'deadline' => null,
        'status' => Task::STATUS_TODO,
    ];

    public ?Task $editing = null;

    protected function rules(): array
    {
        return [
            'form.title' => ['required','string','max:255'],
            'form.description' => ['nullable','string'],
            'form.difficulty' => ['required','integer','between:1,10'],
            'form.desire' => ['required','integer','between:1,10'],
            'form.obligation' => ['required','integer','between:1,10'],
            'form.deadline' => ['nullable','date'],
            'form.status' => ['required','string'],
        ];
    }

    public function create(PriorityEngine $engine): void
    {
        $this->validate();
        $task = new Task($this->form);
        $engine->recalculate($task);
        $task->save();
        $this->resetForm();
        $this->dispatch('task-created', id: $task->id);
    }

    public function edit(int $taskId): void
    {
        $this->editing = Task::findOrFail($taskId);
        $this->form = [
            'title' => $this->editing->title,
            'description' => $this->editing->description,
            'difficulty' => $this->editing->difficulty,
            'desire' => $this->editing->desire,
            'obligation' => $this->editing->obligation,
            'deadline' => optional($this->editing->deadline)->format('Y-m-d H:i:s'),
            'status' => $this->editing->status,
        ];
    }

    public function update(PriorityEngine $engine): void
    {
        if (!$this->editing) {
            return;
        }
        $this->validate();
        $this->editing->fill($this->form);
        $engine->recalculate($this->editing);
        $this->editing->save();
        $this->dispatch('task-updated', id: $this->editing->id);
    }

    public function delete(int $taskId): void
    {
        Task::whereKey($taskId)->delete();
        $this->dispatch('task-deleted', id: $taskId);
    }

    public function archive(int $taskId): void
    {
        Task::whereKey($taskId)->delete();
        $this->dispatch('task-archived', id: $taskId);
    }

    public function restore(int $taskId): void
    {
        $task = Task::withTrashed()->findOrFail($taskId);
        $task->restore();
        $this->dispatch('task-restored', id: $taskId);
    }

    public function updateStatus(int $taskId, string $status, PriorityEngine $engine): void
    {
        $allowed = [
            Task::STATUS_TODO,
            Task::STATUS_DOING,
            Task::STATUS_DONE,
            Task::STATUS_HOLD,
            Task::STATUS_DROPPED,
        ];
        if (!in_array($status, $allowed, true)) {
            return;
        }
        $task = Task::findOrFail($taskId);
        $task->status = $status;
        $engine->recalculate($task);
        $task->save();
        $this->dispatch('task-status-updated', id: $taskId, status: $status);
    }

    public function resetForm(): void
    {
        $this->form = [
            'title' => '',
            'description' => null,
            'difficulty' => 5,
            'desire' => 5,
            'obligation' => 5,
            'deadline' => null,
            'status' => Task::STATUS_TODO,
        ];
        $this->editing = null;
    }

    public function render()
    {
        return view('livewire.tasks.task-manager', [
            'tasks' => Task::orderByPriority()->get()
        ]);
    }
}
