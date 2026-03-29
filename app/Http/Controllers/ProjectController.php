<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Response;

class ProjectController
{
    public function index(Request $request)
    {

        $query = Project::query();

        //  Filtro por ID
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        //  Filtro por nome do projeto
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        //  Filtro por status do projeto
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $query->orderBy('created_at', 'desc');

        $query->withCount('tasks');

        $projects = $query->paginate(10);

        $projectResource = new ProjectCollection($projects);

        return Response::success($projectResource, 'Project listed successfully');

    }

    public function show(Project $project)
    {
        // Carrega contagem de tarefas (performance)
        $project->loadCount('tasks');

        $projectResource = new ProjectResource($project);

        return Response::success($projectResource, 'Project successfully listed.');
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validated());

        $projectResource = new ProjectResource($project);

        return Response::success($projectResource, 'Project created successfully', 201);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        $projectResource = new ProjectResource($project);

        return Response::success($projectResource, 'Project updated successfully');
    }
}
