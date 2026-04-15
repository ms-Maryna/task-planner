<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start">

                {{-- Calendar Sidebar --}}
                <div class="w-full lg:w-72 flex-shrink-0">
                    <div class="bg-white shadow-sm rounded-lg p-5">

                        {{-- Month Navigation --}}
                        <div class="flex items-center justify-between mb-4">
                            <button onclick="prevMonth()"
                                class="p-1 rounded hover:bg-gray-100 text-gray-500 hover:text-gray-700">
                                &#8592;
                            </button>
                            <span id="calendar-month" class="text-sm font-semibold text-gray-700"></span>
                            <button onclick="nextMonth()"
                                class="p-1 rounded hover:bg-gray-100 text-gray-500 hover:text-gray-700">
                                &#8594;
                            </button>
                        </div>

                        {{-- Calendar Grid --}}
                        <div id="calendar-grid" class="grid grid-cols-7 gap-y-1 text-center"></div>

                        {{-- Clear date filter --}}
                        <div id="date-filter-label" class="hidden mt-4 text-center">
                            <span class="text-xs text-indigo-600 font-medium" id="date-filter-text"></span>
                            <button onclick="clearDateFilter()"
                                class="ml-2 text-xs text-gray-400 hover:text-red-500 underline">clear</button>
                        </div>

                    </div>
                </div>

                {{-- Tasks Panel --}}
                <div class="flex-1">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">

                            <h2 class="text-2xl font-semibold text-gray-800 text-center mb-2">
                                My Tasks
                            </h2>
                            <p class="text-center text-gray-400 text-sm mb-6">
                                <span id="task-count"></span>
                            </p>

                            {{-- Success message --}}
                            @if(session('success'))
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                                    {{ session('success') }}
                                </div>
                            @endif

                            {{-- Filter buttons --}}
                            @if(!$tasks->isEmpty())
                                <div class="flex gap-3 mb-6 justify-center">
                                    <button onclick="setStatusFilter('all')" id="btn-all"
                                        class="px-4 py-1 rounded-full text-sm font-medium bg-indigo-600 text-white filter-btn">
                                        All
                                    </button>
                                    <button onclick="setStatusFilter('incomplete')" id="btn-incomplete"
                                        class="px-4 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-600 filter-btn">
                                        Incomplete
                                    </button>
                                    <button onclick="setStatusFilter('complete')" id="btn-complete"
                                        class="px-4 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-600 filter-btn">
                                        Completed
                                    </button>
                                </div>
                            @endif

                            @if($tasks->isEmpty())
                                <p class="text-center text-gray-500 mb-8">You have no tasks yet.</p>
                            @else
                                <table class="w-full border-collapse mb-8">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Title</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Due</th>
                                            <th class="py-3 px-4"></th>
                                            <th class="py-3 px-4"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tasks as $task)
                                            <tr class="border-t border-gray-100 hover:bg-gray-50 task-row"
                                                data-status="{{ $task->is_completed ? 'complete' : 'incomplete' }}"
                                                data-due="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                                                <td class="py-3 px-4">
                                                    <div class="flex items-center gap-3">
                                                        <input type="checkbox"
                                                            {{ $task->is_completed ? 'checked' : '' }}
                                                            disabled
                                                            class="w-4 h-4">
                                                        <a href="{{ route('tasks.show', $task) }}"
                                                            class="{{ $task->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }} hover:text-indigo-600">
                                                            {{ $task->title }}
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4 text-sm text-gray-600">
                                                    {{ $task->due_date ? $task->due_date->format('d/m/y') : '' }}
                                                    {{ $task->due_time ? '@ ' . substr($task->due_time, 0, 5) : '' }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    <a href="{{ route('tasks.edit', $task) }}"
                                                        class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                        Edit
                                                    </a>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this task?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-500 hover:text-red-700 text-sm font-medium">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            <div class="text-center">
                                <a href="{{ route('tasks.create') }}"
                                    class="inline-flex items-center gap-2 px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium">
                                    <span>＋</span>
                                    {{ $tasks->isEmpty() ? 'Create your first task' : 'Create new task' }}
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Task due dates from server
        const dueDates = new Set(@json(
            $tasks->whereNotNull('due_date')
                  ->map(fn($t) => $t->due_date->format('Y-m-d'))
                  ->values()
                  ->toArray()
        ));

        const monthNames = ['January','February','March','April','May','June',
                            'July','August','September','October','November','December'];
        const dayLabels = ['Mo','Tu','We','Th','Fr','Sa','Su'];

        let currentYear = new Date().getFullYear();
        let currentMonth = new Date().getMonth();
        let selectedDate = null;
        let activeStatusFilter = 'all';

        function renderCalendar() {
            document.getElementById('calendar-month').textContent =
                `${monthNames[currentMonth]} ${currentYear}`;

            const grid = document.getElementById('calendar-grid');
            grid.innerHTML = '';

            // Day headers
            dayLabels.forEach(d => {
                const el = document.createElement('div');
                el.textContent = d;
                el.className = 'text-xs font-medium text-gray-400 pb-1';
                grid.appendChild(el);
            });

            const today = new Date().toISOString().split('T')[0];
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const offset = (firstDay === 0 ? 6 : firstDay - 1);
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            // Empty cells before first day
            for (let i = 0; i < offset; i++) {
                grid.appendChild(document.createElement('div'));
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                const hasTask = dueDates.has(dateStr);
                const isToday = dateStr === today;
                const isSelected = dateStr === selectedDate;

                const cell = document.createElement('div');
                cell.className = 'flex flex-col items-center py-0.5 rounded';

                if (isSelected) {
                    cell.classList.add('bg-indigo-100');
                } else if (isToday) {
                    cell.classList.add('bg-gray-50');
                }

                if (hasTask) {
                    cell.classList.add('cursor-pointer', 'hover:bg-indigo-50');
                    cell.addEventListener('click', () => selectDate(dateStr));
                }

                const num = document.createElement('span');
                num.textContent = d;
                num.className = `text-xs ${isToday ? 'font-bold text-indigo-600' : 'text-gray-700'}`;
                cell.appendChild(num);

                if (hasTask) {
                    const dot = document.createElement('div');
                    dot.className = `w-1.5 h-1.5 rounded-full mt-0.5 ${isSelected ? 'bg-indigo-600' : 'bg-indigo-400'}`;
                    cell.appendChild(dot);
                } else {
                    // keep height consistent
                    const spacer = document.createElement('div');
                    spacer.className = 'w-1.5 h-1.5 mt-0.5';
                    cell.appendChild(spacer);
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
            const text = document.getElementById('date-filter-text');
            if (selectedDate) {
                const d = new Date(selectedDate + 'T00:00:00');
                text.textContent = `Showing: ${d.getDate()} ${monthNames[d.getMonth()]}`;
                label.classList.remove('hidden');
            } else {
                label.classList.add('hidden');
            }
        }

        function prevMonth() {
            if (currentMonth === 0) { currentMonth = 11; currentYear--; }
            else currentMonth--;
            renderCalendar();
        }

        function nextMonth() {
            if (currentMonth === 11) { currentMonth = 0; currentYear++; }
            else currentMonth++;
            renderCalendar();
        }

        function setStatusFilter(filter) {
            activeStatusFilter = filter;

            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'text-white');
                btn.classList.add('border', 'border-gray-300', 'text-gray-600');
            });
            const active = document.getElementById('btn-' + filter);
            if (active) {
                active.classList.add('bg-indigo-600', 'text-white');
                active.classList.remove('border', 'border-gray-300', 'text-gray-600');
            }

            applyFilters();
        }

        function applyFilters() {
            const rows = document.querySelectorAll('.task-row');
            let visible = 0;

            rows.forEach(row => {
                const statusMatch = activeStatusFilter === 'all' || row.dataset.status === activeStatusFilter;
                const dateMatch = !selectedDate || row.dataset.due === selectedDate;
                const show = statusMatch && dateMatch;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            const countEl = document.getElementById('task-count');
            if (countEl) {
                countEl.textContent = `${visible} ${visible === 1 ? 'task' : 'tasks'}${selectedDate ? ' on this day' : ' total'}`;
            }
        }

        // Initialise
        renderCalendar();
        applyFilters();
    </script>
</x-app-layout>
