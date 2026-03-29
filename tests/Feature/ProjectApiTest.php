<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase; // Reseta o banco antes de cada teste

    /**
     * TESTE: Criar projeto com sucesso
     */
    public function test_can_create_project(): void
    {
        $payload = [
            'name' => 'Novo Projeto Teste',
            'description' => 'Descrição do projeto de teste',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/projects', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Project created successfully',
            ])
            ->assertJsonPath('data.name', 'Novo Projeto Teste')
            ->assertJsonPath('data.status', 'active');

        //  Verifica no banco de dados
        $this->assertDatabaseHas('projects', [
            'name' => 'Novo Projeto Teste',
            'status' => 'active',
        ]);
    }

    /**
     *  TESTE: Criar projeto sem nome (validação)
     */
    public function test_cannot_create_project_without_name(): void
    {
        $payload = [
            'description' => 'Sem nome',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/projects', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The project name is required.',
            ])
            ->assertJsonValidationErrors('name');
    }

    /**
     * TESTE: Criar projeto com status inválido
     */
    public function test_cannot_create_project_with_invalid_status(): void
    {
        $payload = [
            'name' => 'Projeto Inválido',
            'status' => 'invalid_status',
        ];

        $response = $this->postJson('/api/projects', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The status must be active or archived.',
            ]);
    }

    /**
     *  TESTE: Filtrar projetos por status
     */
    public function test_can_filter_projects_by_status(): void
    {
        Project::factory()->create(['status' => 'active']);
        Project::factory()->create(['status' => 'archived']);

        $response = $this->getJson('/api/projects?status=active');

        $response->assertStatus(200);
    }

    /**
     * TESTE: Projeto não encontrado (404)
     */
    public function test_project_not_found(): void
    {
        $response = $this->getJson('/api/projects/999');

        $response->assertStatus(404);
    }

    /**
     *  TESTE: Atualizar projeto (PATCH)
     */
    public function test_can_update_project(): void
    {
        $project = Project::factory()->create([
            'name' => 'Nome Original',
            'status' => 'active',
        ]);

        $payload = [
            'name' => 'Nome Atualizado',
            'status' => 'archived',
        ];

        $response = $this->patchJson("/api/projects/{$project->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Project updated successfully',
            ])
            ->assertJsonPath('data.name', 'Nome Atualizado')
            ->assertJsonPath('data.status', 'archived');

        //  Verifica no banco
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Nome Atualizado',
            'status' => 'archived',
        ]);
    }

    /**
     *  TESTE: Atualizar apenas status (PATCH parcial)
     */
    public function test_can_update_project_status_only(): void
    {
        $project = Project::factory()->create(['status' => 'active']);

        $payload = [
            'status' => 'archived',
            // Não envia name ou description
        ];

        $response = $this->patchJson("/api/projects/{$project->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'archived');

        //  Nome original permanece
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => $project->name,
            'status' => 'archived',
        ]);
    }
}