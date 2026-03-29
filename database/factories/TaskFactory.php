<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(4), // Ex: "Implementar sistema de login"
            'description' => fake()->paragraph(3),
            'status' => fake()->randomElement(['todo', 'in_progress', 'done']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'due_date' => fake()->optional(0.8)->dateTimeBetween('-1 month', '+2 months'), // 80% têm data
        ];
    }

    /**
     * Tarefa pendente (todo).
     */
    public function todo(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'todo',
        ]);
    }

    /**
     * Tarefa em andamento.
     */
    public function inProgress(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    /**
     * Tarefa concluída.
     */
    public function done(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'done',
        ]);
    }

    /**
     * Tarefa de alta prioridade.
     */
    public function highPriority(): static
    {
        return $this->state(fn(array $attributes) => [
            'priority' => 'high',
        ]);
    }

    /**
     * Tarefa VENCIDA (Overdue) - Crucial para testar o scope.
     */
    public function overdue(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => fake()->randomElement(['todo', 'in_progress']), // Nunca 'done'
            'due_date' => fake()->dateTimeBetween('-2 weeks', '-1 day'), // Data no passado
        ]);
    }
}