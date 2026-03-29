<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes; // ✅ SoftDeletes é obrigatório no teste!

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     */
    protected $casts = [
        'due_date' => 'date', // ✅ Importante para comparações de data
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * 🔗 Relação: Uma tarefa pertence a um projeto.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * 🔍 Scope: Tarefas VENCIDAS (Overdue).
     * ✅ REQUISITO OBRIGATÓRIO DO TESTE (#4)
     * 
     * Critérios:
     * - Status diferente de 'done' (tarefa concluída não está vencida)
     * - due_date menor que hoje (data passada)
     * - due_date não nulo
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now());
    }

    /**
     * 🔍 Scope: Tarefas por status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * 🔍 Scope: Tarefas por prioridade.
     */
    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * 🔍 Scope: Tarefas de alta prioridade.
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    /**
     * 🔍 Scope: Tarefas não excluídas (útil com soft deletes).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * ✅ Verifica se a tarefa está vencida (para uso no frontend).
     */
    public function isOverdue(): bool
    {
        return $this->status !== 'done'
            && $this->due_date
            && $this->due_date->isPast();
    }

    /**
     * ✅ Verifica se a tarefa está concluída.
     */
    public function isDone(): bool
    {
        return $this->status === 'done';
    }
}