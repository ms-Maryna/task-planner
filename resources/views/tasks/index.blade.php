<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page header + stats --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">My Tasks</h1>
                    <p class="text-white/40 text-sm mt-1">Organize, track, and get things done</p>
                </div>
                <div class="flex gap-3">
                    <div class="rounded-xl px-4 py-2.5 text-center flex-shrink-0"
                        style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="text-xl font-bold text-white">{{ $tasks->count() }}</div>
                        <div class="text-xs text-white/40 mt-0.5">Total</div>
                    </div>
                    <div class="rounded-xl px-4 py-2.5 text-center flex-shrink-0"
                        style="background: rgba(52,211,153,0.12); border: 1px solid rgba(52,211,153,0.2);">
                        <div class="text-xl font-bold text-emerald-400">{{ $tasks->where('is_completed', true)->count() }}</div>
                        <div class="text-xs text-emerald-400/60 mt-0.5">Done</div>
                    </div>
                    <div class="rounded-xl px-4 py-2.5 text-center flex-shrink-0"
                        style="background: rgba(251,191,36,0.12); border: 1px solid rgba(251,191,36,0.2);">
                        <div class="text-xl font-bold text-amber-400">{{ $tasks->where('is_completed', false)->count() }}</div>
                        <div class="text-xs text-amber-400/60 mt-0.5">Pending</div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6 items-start">

                {{-- Calendar Sidebar --}}
                <div class="w-full lg:w-72 flex-shrink-0">
                    <div class="rounded-2xl p-5"
                        style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); backdrop-filter: blur(12px);">

                        {{-- Month navigation --}}
                        <div class="flex items-center justify-between mb-4">
                            <button onclick="prevMonth()"
                                class="p-1.5 rounded-lg transition-colors duration-150"
                                style="color: rgba(255,255,255,0.4);"
                                onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white';"
                                onmouseout="this.style.background=''; this.style.color='rgba(255,255,255,0.4)';">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <span id="calendar-month" class="text-sm font-semibold text-white"></span>
                            <button onclick="nextMonth()"
                                class="p-1.5 rounded-lg transition-colors duration-150"
                                style="color: rgba(255,255,255,0.4);"
                                onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white';"
                                onmouseout="this.style.background=''; this.style.color='rgba(255,255,255,0.4)';">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>

                        <div id="calendar-grid" class="grid grid-cols-7 gap-y-1 text-center"></div>

                        {{-- Legend --}}
                        <div class="mt-4 flex gap-3 justify-center" style="font-size: 11px; color: rgba(255,255,255,0.35);">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full inline-block" style="background:#34d399;"></span> Low
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full inline-block" style="background:#fbbf24;"></span> Med
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full inline-block" style="background:#f87171;"></span> High
                            </span>
                        </div>

                        <div id="date-filter-label" class="hidden mt-3 text-center">
                            <span class="text-xs font-medium" style="color: #a5b4fc;" id="date-filter-text"></span>
                            <button onclick="clearDateFilter()"
                                class="ml-2 text-xs underline transition-colors duration-150"
                                style="color: rgba(255,255,255,0.3);"
                                onmouseover="this.style.color='#f87171';"
                                onmouseout="this.style.color='rgba(255,255,255,0.3)';">clear</button>
                        </div>
                    </div>
                </div>

                {{-- Tasks Panel --}}
                <div class="flex-1">
                    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

                        {{-- Panel header (filter bar) --}}
                        <div class="px-6 pt-5 pb-4" style="border-bottom: 1px solid #f3f4f6;">
                            <div class="flex items-center justify-between gap-4">
                                <p class="text-gray-400 text-sm font-medium" id="task-count"></p>
                                @if(!$tasks->isEmpty())
                                    <div class="flex gap-2 flex-shrink-0">
                                        <button onclick="setStatusFilter('all')" id="btn-all"
                                            class="filter-btn px-3 py-1 rounded-full text-xs font-semibold transition-colors duration-150"
                                            style="background:#6366f1; color:white;">All</button>
                                        <button onclick="setStatusFilter('incomplete')" id="btn-incomplete"
                                            class="filter-btn px-3 py-1 rounded-full text-xs font-semibold border border-gray-200 text-gray-500 transition-colors duration-150">Pending</button>
                                        <button onclick="setStatusFilter('complete')" id="btn-complete"
                                            class="filter-btn px-3 py-1 rounded-full text-xs font-semibold border border-gray-200 text-gray-500 transition-colors duration-150">Done</button>
                                    </div>
                                @endif
                            </div>

                            @if(session('success'))
                                <div class="mt-3 flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium"
                                    style="background:#ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ session('success') }}
                                </div>
                            @endif
                        </div>

                        {{-- Task list --}}
                        <div class="p-4">
                            @if($tasks->isEmpty())
                                <div class="text-center py-16">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center"
                                        style="background: #eef2ff;">
                                        <svg class="w-8 h-8" style="color: #a5b4fc;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-semibold mb-1">No tasks yet</p>
                                    <p class="text-gray-400 text-sm">Create your first task to get started</p>
                                </div>
                            @else
                                <div class="space-y-2">
                                    @foreach($tasks as $task)
                                        @php
                                            $today = \Carbon\Carbon::today();
                                            $totalHours = ($task->estimated_hours ?? 0) + $task->subtasks->sum('estimated_hours');
                                            $urgency = null;

                                            if ($task->due_date) {
                                                $daysRemaining = max(0, $today->diffInDays($task->due_date, false));
                                                if ($totalHours > 0) {
                                                    $ratio = $totalHours / max($daysRemaining * 8, 0.5);
                                                    if ($ratio >= 1 || $daysRemaining <= 0)        $urgency = 'red';
                                                    elseif ($ratio >= 0.3 || $daysRemaining <= 2)  $urgency = 'orange';
                                                    else                                           $urgency = 'green';
                                                } else {
                                                    if ($daysRemaining <= 0)       $urgency = 'red';
                                                    elseif ($daysRemaining <= 2)   $urgency = 'orange';
                                                    else                           $urgency = 'green';
                                                }
                                            }

                                            $borderColor = match($urgency) {
                                                'red'    => '#f87171',
                                                'orange' => '#fbbf24',
                                                'green'  => '#34d399',
                                                default  => '#e5e7eb',
                                            };

                                            $subtaskCount     = $task->subtasks->count();
                                            $completedCount   = $task->subtasks->where('is_completed', true)->count();
                                            $subtaskProgress  = $subtaskCount > 0 ? round(($completedCount / $subtaskCount) * 100) : 0;
                                        @endphp

                                        {{-- Parent task card --}}
                                        <div class="task-card rounded-xl overflow-hidden transition-shadow duration-200 hover:shadow-md"
                                            style="border-left: 4px solid {{ $borderColor }}; border-top: 1px solid #f3f4f6; border-right: 1px solid #f3f4f6; border-bottom: 1px solid #f3f4f6; background: {{ $task->is_completed ? '#fafafa' : '#ffffff' }};"
                                            data-status="{{ $task->is_completed ? 'complete' : 'incomplete' }}"
                                            data-due="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                            data-task-id="{{ $task->id }}">

                                            {{-- Main row --}}
                                            <div class="flex items-center gap-3 px-4 py-3">

                                                {{-- Expand toggle --}}
                                                @if($subtaskCount > 0)
                                                    <button onclick="toggleSubtasks({{ $task->id }})"
                                                        id="toggle-{{ $task->id }}"
                                                        class="w-4 flex-shrink-0 text-xs font-bold transition-colors duration-150 hover:text-indigo-500"
                                                        style="color: #d1d5db;">▶</button>
                                                @else
                                                    <span class="w-4 flex-shrink-0"></span>
                                                @endif

                                                {{-- Circle checkbox --}}
                                                <div class="flex-shrink-0 w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                                    style="{{ $task->is_completed ? 'background:#10b981; border-color:#10b981;' : 'border-color:#d1d5db;' }}">
                                                    @if($task->is_completed)
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    @endif
                                                </div>

                                                {{-- Title + progress bar --}}
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <a href="{{ route('tasks.show', $task) }}"
                                                            class="font-semibold hover:text-indigo-600 transition-colors duration-150 truncate
                                                                {{ $task->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }}">
                                                            {{ $task->title }}
                                                        </a>
                                                        @if($subtaskCount > 0)
                                                            <span class="flex-shrink-0 text-xs px-1.5 py-0.5 rounded-full font-medium"
                                                                style="background: #f3f4f6; color: #6b7280;">
                                                                {{ $completedCount }}/{{ $subtaskCount }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    {{-- Inline progress bar for parent tasks with subtasks --}}
                                                    @if($subtaskCount > 0)
                                                        <div class="mt-1.5 w-full rounded-full h-1" style="background:#f3f4f6;">
                                                            <div class="h-1 rounded-full transition-all duration-500"
                                                                style="width:{{ $subtaskProgress }}%; background: {{ $subtaskProgress === 100 ? '#10b981' : '#6366f1' }};"></div>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Due date badge --}}
                                                <div class="flex-shrink-0 hidden sm:block">
                                                    @if($task->due_date)
                                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                                                            style="{{ $urgency === 'red' ? 'background:#fef2f2; color:#dc2626;' : ($urgency === 'orange' ? 'background:#fffbeb; color:#d97706;' : 'background:#ecfdf5; color:#059669;') }}">
                                                            {{ $task->due_date->format('d/m/y') }}
                                                        </span>
                                                    @endif
                                                </div>

                                                {{-- Est hours --}}
                                                <div class="flex-shrink-0 text-xs text-gray-400 w-14 text-right hidden sm:block">
                                                    @if($totalHours > 0)
                                                        {{ $totalHours }}h
                                                        @if($subtaskCount > 0)
                                                            <div class="text-gray-300" style="font-size:10px;">total</div>
                                                        @endif
                                                    @endif
                                                </div>

                                                {{-- Action icons --}}
                                                <div class="flex-shrink-0 flex items-center gap-1.5">
                                                    <a href="{{ route('tasks.edit', $task) }}"
                                                        class="p-1.5 rounded-lg transition-colors duration-150 hover:bg-indigo-50"
                                                        style="color: #d1d5db;" title="Edit"
                                                        onmouseover="this.style.color='#6366f1';"
                                                        onmouseout="this.style.color='#d1d5db';">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                                        onsubmit="return confirm('Delete this task and all its subtasks?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-1.5 rounded-lg transition-colors duration-150 hover:bg-red-50"
                                                            style="color: #d1d5db;" title="Delete"
                                                            onmouseover="this.style.color='#ef4444';"
                                                            onmouseout="this.style.color='#d1d5db';">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- Subtask container (collapsed by default) --}}
                                            @if($subtaskCount > 0)
                                                <div id="subtasks-{{ $task->id }}" style="display:none;">
                                                    @foreach($task->subtasks as $subtask)
                                                        <div class="subtask-row flex items-center gap-3 px-4 py-2.5 transition-colors duration-150"
                                                            style="border-top: 1px solid #f3f4f6; background: #fafafa;"
                                                            onmouseover="this.style.background='#f9f9ff';"
                                                            onmouseout="this.style.background='#fafafa';"
                                                            data-parent-id="{{ $task->id }}"
                                                            data-status="{{ $subtask->is_completed ? 'complete' : 'incomplete' }}"
                                                            data-due="{{ $subtask->due_date ? $subtask->due_date->format('Y-m-d') : '' }}">

                                                            <span class="w-4 flex-shrink-0"></span>

                                                            {{-- Circle checkbox --}}
                                                            <div class="flex-shrink-0 w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                                                style="{{ $subtask->is_completed ? 'background:#10b981; border-color:#10b981;' : 'border-color:#d1d5db;' }}">
                                                                @if($subtask->is_completed)
                                                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                                    </svg>
                                                                @endif
                                                            </div>

                                                            <a href="{{ route('tasks.show', $subtask) }}"
                                                                class="flex-1 text-sm truncate hover:text-indigo-600 transition-colors duration-150
                                                                    {{ $subtask->is_completed ? 'line-through text-gray-400' : 'text-gray-600' }}">
                                                                {{ $subtask->title }}
                                                            </a>

                                                            <div class="flex-shrink-0 flex items-center gap-3 text-xs" style="color:#9ca3af;">
                                                                @if($subtask->due_date)
                                                                    <span>{{ $subtask->due_date->format('d/m/y') }}</span>
                                                                @endif
                                                                @if($subtask->estimated_hours)
                                                                    <span>{{ $subtask->estimated_hours }}h</span>
                                                                @endif
                                                                <a href="{{ route('tasks.edit', $subtask) }}"
                                                                    class="p-1 rounded transition-colors duration-150"
                                                                    style="color:#d1d5db;"
                                                                    onmouseover="this.style.color='#6366f1';"
                                                                    onmouseout="this.style.color='#d1d5db';">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                    </svg>
                                                                </a>
                                                                <form action="{{ route('tasks.destroy', $subtask) }}" method="POST"
                                                                    onsubmit="return confirm('Delete this subtask?')" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="p-1 rounded transition-colors duration-150"
                                                                        style="color:#d1d5db;"
                                                                        onmouseover="this.style.color='#ef4444';"
                                                                        onmouseout="this.style.color='#d1d5db';">
                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Create task button --}}
                            <div class="mt-5 text-center">
                                <a href="{{ route('tasks.create') }}"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg active:scale-95"
                                    style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    {{ $tasks->isEmpty() ? 'Create your first task' : 'New task' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const dateUrgency  = @json($dateUrgency);
        const monthNames   = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        const dayLabels    = ['Mo','Tu','We','Th','Fr','Sa','Su'];

        let currentYear        = new Date().getFullYear();
        let currentMonth       = new Date().getMonth();
        let selectedDate       = null;
        let activeStatusFilter = 'all';
        const expandedTasks    = new Set();

        // --- Calendar ---
        function renderCalendar() {
            document.getElementById('calendar-month').textContent = `${monthNames[currentMonth]} ${currentYear}`;
            const grid = document.getElementById('calendar-grid');
            grid.innerHTML = '';

            dayLabels.forEach(d => {
                const el = document.createElement('div');
                el.textContent = d;
                el.style.cssText = 'font-size:11px; font-weight:600; color:rgba(255,255,255,0.25); padding-bottom:4px;';
                grid.appendChild(el);
            });

            const today       = new Date().toISOString().split('T')[0];
            const firstDay    = new Date(currentYear, currentMonth, 1).getDay();
            const offset      = firstDay === 0 ? 6 : firstDay - 1;
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            for (let i = 0; i < offset; i++) grid.appendChild(document.createElement('div'));

            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr    = `${currentYear}-${String(currentMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                const urgency    = dateUrgency[dateStr];
                const isToday    = dateStr === today;
                const isSelected = dateStr === selectedDate;

                const dotColor = urgency === 'red' ? '#f87171' : urgency === 'orange' ? '#fbbf24' : '#34d399';

                const cell = document.createElement('div');
                cell.style.cssText = 'display:flex; flex-direction:column; align-items:center; padding:2px 0; border-radius:6px; cursor:default; transition:background 0.15s;';

                if (isSelected)  cell.style.background = 'rgba(99,102,241,0.45)';
                else if (isToday) cell.style.background = 'rgba(255,255,255,0.12)';

                if (urgency) cell.style.cursor = 'pointer';

                const num = document.createElement('span');
                num.textContent = d;
                num.style.cssText = `font-size:11px; font-weight:${isToday ? '700' : '400'}; color:${isToday ? '#a5b4fc' : 'rgba(255,255,255,0.7)'};`;
                cell.appendChild(num);

                const dot = document.createElement('div');
                dot.style.cssText = `width:5px; height:5px; border-radius:50%; margin-top:1px; background:${urgency ? dotColor : 'transparent'};`;
                cell.appendChild(dot);

                if (urgency) {
                    cell.addEventListener('mouseenter', () => {
                        if (!isSelected) cell.style.background = 'rgba(255,255,255,0.1)';
                    });
                    cell.addEventListener('mouseleave', () => {
                        if (!isSelected) cell.style.background = isToday ? 'rgba(255,255,255,0.12)' : '';
                    });
                    cell.addEventListener('click', () => selectDate(dateStr));
                }

                grid.appendChild(cell);
            }
        }

        function selectDate(dateStr) {
            selectedDate = selectedDate === dateStr ? null : dateStr;
            renderCalendar();
            updateDateLabel();
            applyFilters();
        }

        function clearDateFilter() {
            selectedDate = null;
            renderCalendar();
            updateDateLabel();
            applyFilters();
        }

        function updateDateLabel() {
            const label = document.getElementById('date-filter-label');
            const text  = document.getElementById('date-filter-text');
            if (selectedDate) {
                const d = new Date(selectedDate + 'T00:00:00');
                text.textContent = `${d.getDate()} ${monthNames[d.getMonth()]} only`;
                label.classList.remove('hidden');
            } else {
                label.classList.add('hidden');
            }
        }

        function prevMonth() {
            if (currentMonth === 0) { currentMonth = 11; currentYear--; } else currentMonth--;
            renderCalendar();
        }

        function nextMonth() {
            if (currentMonth === 11) { currentMonth = 0; currentYear++; } else currentMonth++;
            renderCalendar();
        }

        // --- Status filter ---
        function setStatusFilter(filter) {
            activeStatusFilter = filter;
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.style.background = '';
                btn.style.color      = '#6b7280';
                btn.style.border     = '1px solid #e5e7eb';
            });
            const active = document.getElementById('btn-' + filter);
            if (active) {
                active.style.background = '#6366f1';
                active.style.color      = 'white';
                active.style.border     = '1px solid transparent';
            }
            applyFilters();
        }

        // --- Combined filter (status + date, including subtask dates) ---
        function applyFilters() {
            const cards = document.querySelectorAll('.task-card');
            let visible = 0;

            cards.forEach(card => {
                const statusMatch = activeStatusFilter === 'all' || card.dataset.status === activeStatusFilter;

                // Check parent due date, then fall back to checking subtask due dates
                let dateMatch = !selectedDate || card.dataset.due === selectedDate;
                if (!dateMatch && selectedDate) {
                    const taskId = card.dataset.taskId;
                    const subs   = document.querySelectorAll(`.subtask-row[data-parent-id="${taskId}"]`);
                    subs.forEach(sub => { if (sub.dataset.due === selectedDate) dateMatch = true; });
                }

                const show = statusMatch && dateMatch;
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            const countEl = document.getElementById('task-count');
            if (countEl) {
                countEl.textContent = `${visible} ${visible === 1 ? 'task' : 'tasks'}${selectedDate ? ' on selected day' : ''}`;
            }
        }

        // --- Expand / collapse subtasks ---
        function toggleSubtasks(taskId) {
            const id        = String(taskId);
            const container = document.getElementById('subtasks-' + id);
            const btn       = document.getElementById('toggle-' + id);

            if (!container) return;

            if (expandedTasks.has(id)) {
                expandedTasks.delete(id);
                container.style.display = 'none';
                if (btn) btn.textContent = '▶';
            } else {
                expandedTasks.add(id);
                container.style.display = 'block';
                if (btn) btn.textContent = '▼';
            }
        }

        renderCalendar();
        applyFilters();
    </script>
</x-app-layout>
