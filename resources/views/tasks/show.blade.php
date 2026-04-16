<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back link --}}
            <a href="{{ $task->parent_id ? route('tasks.show', $task->parent_id) : route('tasks.index') }}"
                class="inline-flex items-center gap-1.5 text-sm font-medium mb-6 transition-colors duration-150"
                style="color: rgba(255,255,255,0.5);"
                onmouseover="this.style.color='white';"
                onmouseout="this.style.color='rgba(255,255,255,0.5)';">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ $task->parent ? $task->parent->title : 'My Tasks' }}
            </a>

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

                {{-- Gradient header --}}
                @php
                    $today = \Carbon\Carbon::today();
                    $totalHours = ($task->estimated_hours ?? 0) + $task->subtasks->sum('estimated_hours');
                    $urgency = null;

                    if ($task->due_date) {
                        $daysRemaining = max(0, $today->diffInDays($task->due_date, false));
                        if ($totalHours > 0) {
                            $ratio = $totalHours / max($daysRemaining * 8, 0.5);
                            if ($ratio >= 1 || $daysRemaining <= 0)       $urgency = 'red';
                            elseif ($ratio >= 0.3 || $daysRemaining <= 2) $urgency = 'orange';
                            else                                           $urgency = 'green';
                        } else {
                            if ($daysRemaining <= 0)       $urgency = 'red';
                            elseif ($daysRemaining <= 2)   $urgency = 'orange';
                            else                           $urgency = 'green';
                        }
                    }
                @endphp

                <div class="px-6 py-6" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="flex-1">
                            @if($task->parent)
                                <p class="text-indigo-200 text-xs font-medium mb-1.5">
                                    ↳ Subtask of {{ $task->parent->title }}
                                </p>
                            @endif
                            <h1 class="text-xl font-bold text-white leading-tight {{ $task->is_completed ? 'line-through opacity-60' : '' }}">
                                {{ $task->title }}
                            </h1>
                        </div>
                        <span class="flex-shrink-0 px-3 py-1 rounded-full text-xs font-bold"
                            style="{{ $task->is_completed ? 'background:rgba(52,211,153,0.25); color:#a7f3d0;' : 'background:rgba(255,255,255,0.15); color:white;' }}">
                            {{ $task->is_completed ? '✓ Done' : '⏳ Pending' }}
                        </span>
                    </div>

                    {{-- Meta badges --}}
                    <div class="flex flex-wrap gap-2">
                        @if($task->due_date)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium"
                                style="background: rgba(255,255,255,0.15); color: white;">
                                📅 {{ $task->due_date->format('d M Y') }}
                                @if($task->due_time)
                                    <span style="color:rgba(255,255,255,0.6);">@ {{ substr($task->due_time, 0, 5) }}</span>
                                @endif
                            </span>
                        @endif
                        @if($totalHours > 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium"
                                style="background: rgba(255,255,255,0.15); color: white;">
                                ⏱ {{ $totalHours }}h
                                @if($task->subtasks->count() > 0 && ($task->estimated_hours ?? 0) > 0)
                                    <span style="color:rgba(255,255,255,0.5); font-size:10px;">total</span>
                                @endif
                            </span>
                        @endif
                        @if($urgency)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium"
                                style="{{ $urgency === 'red' ? 'background:rgba(248,113,113,0.25); color:#fca5a5;' : ($urgency === 'orange' ? 'background:rgba(251,191,36,0.25); color:#fde68a;' : 'background:rgba(52,211,153,0.25); color:#a7f3d0;') }}">
                                {{ $urgency === 'red' ? '🔴 High urgency' : ($urgency === 'orange' ? '🟡 Medium urgency' : '🟢 Low urgency') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Card body --}}
                <div class="p-6">

                    @if(session('success'))
                        <div class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium mb-6"
                            style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46;">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Description --}}
                    @if($task->description)
                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Description</label>
                            <p class="text-gray-700 leading-relaxed rounded-xl px-4 py-3" style="background:#f9fafb; border:1px solid #f3f4f6;">
                                {{ $task->description }}
                            </p>
                        </div>
                    @endif

                    {{-- Quick complete button (only if no subtasks) --}}
                    @if(!$task->is_completed && $task->subtasks->isEmpty())
                        <form action="{{ route('tasks.update', $task) }}" method="POST" class="mb-6">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="title"           value="{{ $task->title }}">
                            <input type="hidden" name="description"     value="{{ $task->description }}">
                            <input type="hidden" name="due_date"        value="{{ $task->due_date?->format('Y-m-d') }}">
                            <input type="hidden" name="due_time"        value="{{ $task->due_time ? substr($task->due_time, 0, 5) : '' }}">
                            <input type="hidden" name="estimated_hours" value="{{ $task->estimated_hours }}">
                            <input type="hidden" name="is_completed"    value="1">
                            <button type="submit"
                                class="w-full py-2.5 rounded-xl font-semibold text-sm text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg active:scale-95"
                                style="background: linear-gradient(135deg, #10b981, #059669);">
                                ✓ Mark as Completed
                            </button>
                        </form>
                    @endif

                    {{-- Subtasks section --}}
                    @if($task->subtasks->count() > 0)
                        @php
                            $progress = round(($task->subtasks->where('is_completed', true)->count() / $task->subtasks->count()) * 100);
                        @endphp
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Subtasks</label>
                                <span class="text-xs font-semibold"
                                    style="color: {{ $progress === 100 ? '#10b981' : '#6b7280' }};">
                                    {{ $task->subtasks->where('is_completed', true)->count() }}/{{ $task->subtasks->count() }} complete
                                </span>
                            </div>

                            {{-- Progress bar --}}
                            <div class="w-full rounded-full h-2 mb-4" style="background:#f3f4f6;">
                                <div class="h-2 rounded-full transition-all duration-500"
                                    style="width:{{ $progress }}%; background: {{ $progress === 100 ? '#10b981' : '#6366f1' }};"></div>
                            </div>

                            {{-- Subtask list --}}
                            <div class="space-y-2">
                                @foreach($task->subtasks as $subtask)
                                    <div class="flex items-center justify-between rounded-xl px-4 py-2.5 transition-colors duration-150"
                                        style="border:1px solid #f3f4f6; background:#fafafa;"
                                        onmouseover="this.style.background='#f5f5ff';"
                                        onmouseout="this.style.background='#fafafa';">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="flex-shrink-0 w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                                style="{{ $subtask->is_completed ? 'background:#10b981; border-color:#10b981;' : 'border-color:#d1d5db;' }}">
                                                @if($subtask->is_completed)
                                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <a href="{{ route('tasks.show', $subtask) }}"
                                                class="text-sm truncate hover:text-indigo-600 transition-colors duration-150
                                                    {{ $subtask->is_completed ? 'line-through text-gray-400' : 'text-gray-700' }}">
                                                {{ $subtask->title }}
                                            </a>
                                        </div>
                                        <div class="flex-shrink-0 flex items-center gap-3 text-xs text-gray-400 ml-3">
                                            @if($subtask->due_date)
                                                <span>{{ $subtask->due_date->format('d/m/y') }}</span>
                                            @endif
                                            @if($subtask->estimated_hours)
                                                <span>{{ $subtask->estimated_hours }}h</span>
                                            @endif
                                            <a href="{{ route('tasks.edit', $subtask) }}"
                                                class="font-medium hover:text-indigo-600 transition-colors duration-150">Edit</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Add subtask button (only for top-level tasks) --}}
                    @if(!$task->parent_id)
                        <div class="mb-6">
                            <a href="{{ route('tasks.create', ['parent_id' => $task->id]) }}"
                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 hover:border-indigo-400 hover:text-indigo-600"
                                style="border: 2px dashed #e5e7eb; color: #9ca3af;">
                                ＋ Add subtask
                            </a>
                        </div>
                    @endif

                    {{-- Action buttons --}}
                    <div class="flex gap-3 pt-2" style="border-top: 1px solid #f3f4f6;">
                        <a href="{{ $task->parent_id ? route('tasks.show', $task->parent_id) : route('tasks.index') }}"
                            class="flex-1 py-2.5 rounded-xl text-sm font-medium text-center transition-colors duration-150"
                            style="border:1px solid #e5e7eb; color:#4b5563;"
                            onmouseover="this.style.background='#f9fafb';"
                            onmouseout="this.style.background='';">
                            ← Back
                        </a>
                        <a href="{{ route('tasks.edit', $task) }}"
                            class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-center text-white transition-all duration-200 hover:opacity-90"
                            style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                            Edit Task
                        </a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                            onsubmit="return confirm('Delete this task{{ $task->subtasks->count() > 0 ? ' and all its subtasks' : '' }}?')" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full py-2.5 rounded-xl text-sm font-medium transition-colors duration-150"
                                style="border:1px solid #fecaca; color:#ef4444;"
                                onmouseover="this.style.background='#fef2f2';"
                                onmouseout="this.style.background='';">
                                Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
