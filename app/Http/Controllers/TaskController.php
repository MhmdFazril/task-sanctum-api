<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $tasks = Task::query()->with('user')->where('user_id', $request->user()->id);

        if (!empty($request->input('search'))) {
            // memakai closure function karna kondisi or sementara ada kondisi and lagi dibawah nya
            $tasks->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->input('search') . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->input('search') . '%');
            });
        }

        if ($request->boolean('completed')) {
            $tasks->where('isCompleted', $request->boolean('completed'));
        }

        $tasks = $tasks->paginate(perPage: $size, page: $page);

        return $tasks;
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
        Gate::authorize('view', $task);
        return $task;
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);
        $data = $request->validated();

        $task->update($data);

        return response()->json([
            'message' => 'Update success',
            'task' => $task
        ], 202);
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);
        $task->delete();

        return [
            'message' => "Task Deleted"
        ];
    }
}
