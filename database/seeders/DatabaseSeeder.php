<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🎯 Criar 5 Projetos Ativos
        Project::factory()
            ->count(5)
            ->active()
            ->hasTasks(8) // Cada projeto terá 8 tarefas
            ->create();

        // 📦 Criar 2 Projetos Arquivados
        Project::factory()
            ->count(2)
            ->archived()
            ->hasTasks(5)
            ->create();

        // ⚠️ Criar Tarefas Vencidas (Overdue) em projetos específicos
        // Isso garante que o teste do scopeOverdue() funcione
        $activeProjects = Project::where('status', 'active')->limit(3)->get();

        foreach ($activeProjects as $project) {
            Task::factory()
                ->count(2)
                ->overdue() // Usa o estado 'overdue' da factory
                ->for($project)
                ->create();
        }

        // 🔴 Criar algumas tarefas de Alta Prioridade explícitas
        Task::factory()
            ->count(5)
            ->highPriority()
            ->todo()
            ->for(Project::where('status', 'active')->inRandomOrder()->first())
            ->create();
    }
}
