<?php

namespace Database\Factories;

use App\Models\Log;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $tasks ;
    

    public function definition(): array
    {
        $task_id = Task::inRandomOrder()->first()->id;
        return [
            'task_id' => $task_id,
            'notes' => $this->faker->sentence(),
            'completed_date' => $this->faker->dateTimeBetween('2026-04-01', 'now')->format('Y-m-d'),
        ];
    }


}
