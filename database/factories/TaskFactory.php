<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\Task;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title'=>$this->faker->sentence(),
            'priority'=>$this->faker->randomElement(['pending'|| 'completed']),
            'due_date'=>$this->faker->dateTimeBetween('now', '+1 month'),
            'description'=>$this->faker->sentence(),
            'status'=> $this->faker->randomElement(['low'|| 'medium' || 'high']),
            'completed_at'=> now(),
            'project_id'=> Project::factory(),
        ];
    }
}
