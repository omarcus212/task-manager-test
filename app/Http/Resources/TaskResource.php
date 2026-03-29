<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            //  Campos essenciais da tarefa
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,

            // Data de vencimento (formatada)
            'due_date' => $this->due_date?->format('Y-m-d'),

            // Indicador de tarefa vencida (calculado no Model)
            'is_overdue' => $this->isOverdue(),

            // Projeto relacionado (se carregado)
            'project' => when($this->relationLoaded('project'), function () {
                return [
                    'id' => $this->project->id,
                    'name' => $this->project->name,
                ];
            }),

            // Datas formatadas em ISO
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
