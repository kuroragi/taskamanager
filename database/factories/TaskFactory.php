<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $statuses = [
            Task::STATUS_TODO,
            Task::STATUS_DOING,
            Task::STATUS_DONE,
            Task::STATUS_HOLD,
            Task::STATUS_DROPPED,
        ];

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'difficulty' => $this->faker->numberBetween(1, 10),
            'desire' => $this->faker->numberBetween(1, 10),
            'obligation' => $this->faker->numberBetween(1, 10),
            'deadline' => $this->faker->optional()->dateTimeBetween('now', '+20 days'),
            'status' => $this->faker->randomElement($statuses),
            'priority_score' => 0,
        ];
    }
}
