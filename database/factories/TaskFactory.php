<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
        return [
            'title' => fake()->text(100),
            'description' => fake()->optional()->text(1000),
            'due_date' => fake()->optional()->dateTimeInInterval('now', '+1 year'),
            'completed' => fake()->boolean(),
            'user_id' => User::all('id')->random()->id ?? null,
        ];
    }

    public function notCompleted(): TaskFactory
    {
        return $this->state(fn (array $attributes) => [
            'completed' => false
        ]);
    }

    public function withUser(User $user): TaskFactory
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id
        ]);
    }


}
