<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\SoftDeletes;
use Response;
use Illuminate\Http\Request;

class TaskController
{
    use SoftDeletes;

    public function index(Project $project, Request $request)
    {
        $query = $project->tasks();

        if ($request->has('title')) {
            $query->where('title', $request->title);
        }

        //  Filtro por Status (todo, in_progress, done)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        //  Filtro por Prioridade (low, medium, high)
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        //  Filtro por Tarefas Vencidas (overdue)
        if ($request->boolean('overdue')) {
            $query->overdue(); // Usa o scope do Model Task
        }

        $query->orderBy('created_at', 'desc');
        //  Excluir tarefas com soft delete (padrão do Eloquent)
        $query->whereNull('deleted_at');

        $tasks = $query->paginate(10);

        $tasksResource = new TaskCollection($tasks);

        return Response::success($tasksResource, 'Tasks listed successfully', 200);

    }

    public function show(Project $project, Task $task)
    {

        if ($task->project_id !== $project->id) {
            return Response::error('Task not found in this project', 404);
        }

        // Carrega o projeto relacionado
        $task->load('project');

        $taskResource = new TaskResource($task);

        return Response::success($taskResource, 'Task listed successfully');

    }

    public function store(Project $project, StoreTaskRequest $request)
    {
        // Cria a tarefa já vinculada ao projeto
        $task = $project->tasks()->create($request->validated());

        // Carrega o projeto relacionado para o Resource
        $task->load('project');

        $taskResource = new TaskResource($task);

        return Response::success($taskResource, 'Task created successfully', 201);

    }

    public function update(Project $project, Task $task, UpdateTaskRequest $request)
    {
        if ($task->project_id !== $project->id) {
            return Response::error('Task not found', 404);
        }

        // Atualiza APENAS os campos validados (status e/ou priority)
        $task->update($request->validated());

        // Carrega o projeto relacionado para o Resource
        $task->load('project');

        $taskResource = new TaskResource($task);

        return Response::success($taskResource, 'Task updated successfully');
    }

    /**
     * Excluir tarefa (soft delete)
     */
    public function destroy(Project $project, Task $task)
    {
        if ($task->project_id !== $project->id) {
            return Response::error('Task not found', 404);
        }

        // ✅ Soft delete (não remove do banco, só marca deleted_at)
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ], 200);
    }

}
