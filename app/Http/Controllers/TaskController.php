<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()
            ->whereNull('parent_id')
            ->with('subtasks')
            ->get();

        $allTasksForCalendar = Auth::user()->tasks()
            ->whereNotNull('due_date')
            ->get();

        $dateUrgency = [];
        $today = Carbon::today();
        $urgencyOrder = ['green' => 0, 'orange' => 1, 'red' => 2];

        foreach ($allTasksForCalendar as $task) {
            $dateStr = $task->due_date->format('Y-m-d');
            $daysRemaining = max(0, $today->diffInDays($task->due_date, false));

            if ($task->estimated_hours) {
                $ratio = $task->estimated_hours / max($daysRemaining * 8, 0.5);
                if ($ratio >= 1 || $daysRemaining <= 0) $level = 'red';
                elseif ($ratio >= 0.3 || $daysRemaining <= 2) $level = 'orange';
                else $level = 'green';
            } else {
                if ($daysRemaining <= 0) $level = 'red';
                elseif ($daysRemaining <= 2) $level = 'orange';
                else $level = 'green';
            }

            $existing = $dateUrgency[$dateStr] ?? 'green';
            if ($urgencyOrder[$level] > $urgencyOrder[$existing]) {
                $dateUrgency[$dateStr] = $level;
            }
        }

        return view('tasks.index', compact('tasks', 'dateUrgency'));
    }

    public function create(Request $request)
    {
        $parent = null;
        if ($request->has('parent_id')) {
            $parent = Task::findOrFail($request->parent_id);
            abort_if($parent->user_id !== Auth::id(), 403);
        }
        return view('tasks.create', compact('parent'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|min:3|max:255',
            'description'     => 'nullable|string|max:1000',
            'due_date'        => 'nullable|date|after_or_equal:today',
            'due_time'        => 'nullable|date_format:H:i',
            'estimated_hours' => 'nullable|numeric|min:0.25|max:24',
            'parent_id'       => 'nullable|integer|exists:tasks,id',
        ]);

        if (!empty($validated['parent_id'])) {
            $parent = Task::find($validated['parent_id']);
            abort_if($parent->user_id !== Auth::id(), 403);
        }

        Auth::user()->tasks()->create($validated);

        if (!empty($validated['parent_id'])) {
            return redirect()->route('tasks.show', $validated['parent_id'])
                ->with('success', 'Subtask created successfully!');
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    public function edit(string $id)
    {
        $task = Task::findOrFail($id);
        abort_if($task->user_id !== Auth::id(), 403);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);
        abort_if($task->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title'           => 'required|string|min:3|max:255',
            'description'     => 'nullable|string|max:1000',
            'due_date'        => 'nullable|date',
            'due_time'        => 'nullable|date_format:H:i',
            'estimated_hours' => 'nullable|numeric|min:0.25|max:24',
            'is_completed'    => 'boolean',
        ]);

        $validated['is_completed'] = $request->boolean('is_completed');

        $task->update($validated);

        // Auto-complete parent if all subtasks are done
        if ($task->parent_id) {
            $parent = Task::find($task->parent_id);
            if ($parent && $parent->subtasks()->where('is_completed', false)->count() === 0) {
                $parent->update(['is_completed' => true]);
            }
        }

        if ($task->parent_id) {
            return redirect()->route('tasks.show', $task->parent_id)
                ->with('success', 'Task updated successfully!');
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        abort_if($task->user_id !== Auth::id(), 403);
        $parentId = $task->parent_id;
        $task->delete();

        if ($parentId) {
            return redirect()->route('tasks.show', $parentId)
                ->with('success', 'Subtask deleted successfully!');
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    public function show(string $id)
    {
        $task = Task::with('subtasks', 'parent')->findOrFail($id);
        abort_if($task->user_id !== Auth::id(), 403);
        return view('tasks.show', compact('task'));
    }
}
