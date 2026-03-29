<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TESTE: Criar tarefa com sucesso
     */
    public function test_can_create_task(): void
    {
        $project = Project::factory()->create();

        $payload = [
            'title' => 'Nova Tarefa Teste',
            'description' => 'Descrição da tarefa',
            'status' => 'todo',
            'priority' => 'high',
            'due_date' => '2026-04-01',
        ];

        $response = $this->postJson("/api/projects/{$project->id}/tasks", $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Task created successfully',
            ])
            ->assertJsonPath('data.title', 'Nova Tarefa Teste')
            ->assertJsonPath('data.priority', 'high');

        // Verifica no banco
        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'title' => 'Nova Tarefa Teste',
            'priority' => 'high',
        ]);
    }

    /**
     *  TESTE: Criar tarefa sem título (validação)
     */
    public function test_cannot_create_task_without_title(): void
    {
        $project = Project::factory()->create();

        $payload = [
            'description' => 'Sem título',
            'status' => 'todo',
            'priority' => 'high',
        ];

        $response = $this->postJson("/api/projects/{$project->id}/tasks", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /**
     * TESTE: Criar tarefa com prioridade inválida
     */
    public function test_cannot_create_task_with_invalid_priority(): void
    {
        $project = Project::factory()->create();

        $payload = [
            'title' => 'Tarefa Inválida',
            'priority' => 'invalid_priority',
            'status' => 'todo',
        ];

        $response = $this->postJson("/api/projects/{$project->id}/tasks", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('priority');

    }


    /**
     * TESTE: Filtrar tarefas por status
     */
    public function test_can_filter_tasks_by_status(): void
    {
        $project = Project::factory()->create();
        Task::factory()->for($project)->create(['status' => 'todo']);
        Task::factory()->for($project)->create(['status' => 'done']);

        $response = $this->getJson("/api/projects/{$project->id}/tasks?status=todo");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status', 'todo');
    }

    /**
     *  TESTE: Filtrar tarefas por prioridade
     */
    public function test_can_filter_tasks_by_priority(): void
    {
        $project = Project::factory()->create();
        Task::factory()->for($project)->create(['priority' => 'high']);
        Task::factory()->for($project)->create(['priority' => 'low']);

        $response = $this->getJson("/api/projects/{$project->id}/tasks?priority=high");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.priority', 'high');
    }

    /**
     * TESTE: Filtrar tarefas vencidas (overdue)
     */
    public function test_can_filter_overdue_tasks(): void
    {
        $project = Project::factory()->create();

        // Tarefa vencida (data no passado, não concluída)
        Task::factory()->for($project)->create([
            'status' => 'todo',
            'due_date' => now()->subDays(5),
        ]);

        // Tarefa não vencida (data no futuro)
        Task::factory()->for($project)->create([
            'status' => 'todo',
            'due_date' => now()->addDays(5),
        ]);

        // Tarefa concluída (não conta como vencida)
        Task::factory()->for($project)->create([
            'status' => 'done',
            'due_date' => now()->subDays(5),
        ]);

        $response = $this->getJson("/api/projects/{$project->id}/tasks?overdue=true");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.is_overdue', true);
    }

    /**
     *  TESTE: Tarefa de outro projeto (404)
     */
    public function test_task_from_another_project_returns_404(): void
    {
        $project1 = Project::factory()->create();
        $project2 = Project::factory()->create();
        $task = Task::factory()->for($project2)->create();

        // Tenta acessar tarefa do projeto 2 via projeto 1
        $response = $this->getJson("/api/projects/{$project1->id}/tasks/{$task->id}");

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Task not found in this project',
            ]);
    }

    /**
     * TESTE: Atualizar status da tarefa (PATCH)
     */
    public function test_can_update_task_status(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->for($project)->create(['status' => 'todo']);

        $payload = [
            'status' => 'in_progress',
        ];

        $response = $this->patchJson("/api/projects/{$project->id}/tasks/{$task->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task updated successfully',
            ])
            ->assertJsonPath('data.status', 'in_progress');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'in_progress',
        ]);
    }

    /**
     *  TESTE: Atualizar prioridade da tarefa (PATCH)
     */
    public function test_can_update_task_priority(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->for($project)->create(['priority' => 'low']);

        $payload = [
            'priority' => 'high',
        ];

        $response = $this->patchJson("/api/projects/{$project->id}/tasks/{$task->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.priority', 'high');
    }

    /**
     * TESTE: Atualizar ambos status e prioridade
     */
    public function test_can_update_task_status_and_priority(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->for($project)->create([
            'status' => 'todo',
            'priority' => 'low',
        ]);

        $payload = [
            'status' => 'done',
            'priority' => 'high',
        ];

        $response = $this->patchJson("/api/projects/{$project->id}/tasks/{$task->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'done')
            ->assertJsonPath('data.priority', 'high');
    }

    /**
     * TESTE: Não pode atualizar título (somente status/prioridade)
     */
    public function test_cannot_update_task_title(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->for($project)->create(['title' => 'Título Original']);

        $payload = [
            'title' => 'Novo Título',
        ];

        $response = $this->patchJson("/api/projects/{$project->id}/tasks/{$task->id}", $payload);

        $response->assertStatus(200);

        // Título permanece original (campo ignorado na validação)
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Título Original',
        ]);
    }

    /**
     * TESTE: Excluir tarefa (Soft Delete)
     */
    public function test_can_soft_delete_task(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->for($project)->create();

        $response = $this->deleteJson("/api/projects/{$project->id}/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task deleted successfully',
            ]);

        // Verifica soft delete (deleted_at preenchido)
        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);

        // Tarefa não aparece na listagem normal
        $response = $this->getJson("/api/projects/{$project->id}/tasks");
        $response->assertJsonCount(0, 'data');
    }

}