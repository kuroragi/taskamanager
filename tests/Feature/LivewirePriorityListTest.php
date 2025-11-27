<?php

namespace Tests\Feature;

use App\Livewire\Tasks\PriorityList;
use App\Models\Task;
use Livewire\Livewire;
use Tests\TestCase;

class LivewirePriorityListTest extends TestCase
{
    public function test_list_returns_sorted_tasks(): void
    {
        Task::factory()->create(['difficulty' => 10, 'desire' => 1, 'obligation' => 1, 'deadline' => null]);
        Task::factory()->create(['difficulty' => 1, 'desire' => 9, 'obligation' => 9, 'deadline' => now()->addDay()]);

        Livewire::test(PriorityList::class)
            ->assertViewIs('livewire.placeholder')
            ->call('render');

        $component = app(PriorityList::class);
        $list = $component->list();
        $this->assertTrue($list->first()->priority_score >= $list->last()->priority_score);
    }

    public function test_nearest_deadline_returns_ascending_deadlines(): void
    {
        Task::factory()->create(['deadline' => now()->addDays(3)]);
        Task::factory()->create(['deadline' => now()->addDay()]);
        Task::factory()->create(['deadline' => now()->addDays(5)]);

        $component = app(PriorityList::class);
        $deadlines = $component->nearestDeadline();
        $this->assertTrue($deadlines->first()->deadline <= $deadlines->last()->deadline);
    }

    public function test_easiest_returns_ascending_difficulty(): void
    {
        Task::factory()->create(['difficulty' => 8]);
        Task::factory()->create(['difficulty' => 2]);
        Task::factory()->create(['difficulty' => 5]);

        $component = app(PriorityList::class);
        $easiest = $component->easiest();
        $this->assertSame(2, $easiest->first()->difficulty);
    }

    public function test_search_and_deadline_filters_in_list(): void
    {
        Task::factory()->create(['title' => 'Belajar PHP', 'deadline' => now()->addDay()]);
        Task::factory()->create(['title' => 'Olahraga', 'deadline' => now()->addDays(7)]);
        Task::factory()->create(['title' => 'Kemarin', 'deadline' => now()->subDay()]);

        $component = Livewire::test(PriorityList::class)
            ->set('search', 'Belajar')
            ->set('deadlineFilter', 'soon');

        $component->call('render');
        $instance = app(\App\Livewire\Tasks\PriorityList::class);
        $list = $instance->list();

        $this->assertTrue($list->every(function ($t) {
            return $t->deadline && $t->deadline->between(now(), now()->addDays(3)) && str_contains($t->title, 'Belajar');
        }));
    }
}
