<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TaskController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    public function index()
    {
        return Task::all();
    }

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();

        // cara 1 manual
        // $data['user_id'] = $request->user()->id;
        // $task = Task::create($data);

        // cara 2 relasi eloquent
        $task = $request->user()->tasks()->create($data);

        return response()->json([
            'message' => 'Create success',
            'task' => $task
        ], 201);
    }

    public function show(Task $task)
    {
        return $task;
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();

        $task->update($data);

        return response()->json([
            'message' => 'Update success',
            'task' => $task
        ], 202);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return [
            'message' => "Task Deleted"
        ];
    }
}
