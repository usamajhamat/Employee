<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Log;

class TodoController extends Controller
{
    public function store(Request $request)
    {
        Log::info($request);
        $validated = $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in-progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $todo = Todo::create($validated);

        return response()->json([
            'message' => 'ToDo created successfully.',
            'data' => $todo
        ], 201);
    }


    public function show()
    {
        $todos = Todo::get();

        return $todos;
    }


    public function getTodoDetails(Request $request)
    {
        //  Log::info($request);
        $todo = Todo::find($request->input('todoId'));
        // Log::info($todo);
        if ($todo) {
            return response()->json($todo, 200);
        } else {
            return response()->json(['message' => 'Todo not found'], 404);
        }
    }


    public function update(Request $request)
    {
        Log::info($request);
        $todo = Todo::find($request->input('todoId'));
        Log::info($todo);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        $todo->heading = $request->input('heading');
        $todo->description = $request->input('description');
        $todo->status = $request->input('status');
        $todo->priority = $request->input('priority');
        $todo->due_date = $request->input('due_date');
        $todo->save();


    }

    public function destroy(Request $request)
    {
        Log::info($request);
        $todo = Todo::find($request->input('todoId'));
        if ($todo) {
            $todo->delete();
            return response()->json(['message' => 'Todo deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Todo not found'], 404);
        }
    }
}
