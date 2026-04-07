<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Auth::user()->tasks()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }


    public function store(Request $request)
    {
    $validated = $request->validate([
        'title' => 'required|string|min:3|max:255',
        'description' => 'nullable|string|max:1000',
        'due_date' => 'nullable|date|after_or_equal:today',
        'due_time' => 'nullable|date_format:H:i',
    ]);

    Auth::user()->tasks()->create($validated);
    return redirect()->route('tasks.index')
        ->with('success', 'Task created successfully!');
    }
    public function edit(string $id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, string $id)
    {
    $task = Task::findOrFail($id);
    
    abort_if($task->user_id !== Auth::id(), 403);
    
    $validated = $request->validate([
        'title' => 'required|string|min:3|max:255',
        'description' => 'nullable|string|max:1000',
        'due_date' => 'nullable|date',
        'due_time' => 'nullable|date_format:H:i',
        'is_completed' => 'boolean',
    ]);

    $task->update($validated);
    return redirect()->route('tasks.index')
        ->with('success', 'Task updated successfully!');
    }
    
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('tasks.index');
    }

    public function show(string $id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.show', compact('task'));
    }
}
