<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;



//  PROJECTS
Route::get('/projects', [ProjectController::class, 'index']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);
Route::patch('/projects/{project}', [ProjectController::class, 'update']);


//  TASKS
Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
Route::get('/projects/{project}/tasks/{task}', [TaskController::class, 'show']);
Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);
Route::patch('/projects/{project}/tasks/{task}', [TaskController::class, 'update']);
Route::delete('/projects/{project}/tasks/{task}', [TaskController::class, 'destroy']);
