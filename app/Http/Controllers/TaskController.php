<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found.'], 404);
        }
    
        return response()->json($tasks, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
        ]);

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    public function show($id)
    {
        try {
            $task = Task::findOrFail($id);
            return response()->json($task, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No task found for the given ID.'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:pending,completed',
        ]);

        $task->update($validated);

        return response()->json($task, 200);
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found.'], 404);
        }
    
        $task->delete();
    
        return response()->json(['message' => 'Task deleted successfully.'], 200);
    }
}

