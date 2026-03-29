<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 🔗 Relação: Um projeto tem muitas tarefas.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * 🔗 Relação: Apenas tarefas ativas (não soft deleted).
     */
    public function activeTasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * 📊 Accessor: Contagem de tarefas vencidas (overdue).
     * Usado no API Resource para exibir no JSON.
     */
    public function getOverdueTasksCountAttribute(): int
    {
        return $this->tasks()
            ->where('status', '!=', 'done')
            ->where('due_date', '<', now())
            ->count();
    }

    /**
     * 📊 Accessor: Contagem de tarefas por status.
     */
    public function getTasksByStatusAttribute(): array
    {
        return [
            'todo' => $this->tasks()->where('status', 'todo')->count(),
            'in_progress' => $this->tasks()->where('status', 'in_progress')->count(),
            'done' => $this->tasks()->where('status', 'done')->count(),
        ];
    }

    /**
     * 🔍 Scope: Apenas projetos ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * 🔍 Scope: Apenas projetos arquivados.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }
}