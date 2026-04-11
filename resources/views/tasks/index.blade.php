<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h2 class="text-2xl font-semibold text-gray-800 text-center mb-8">
                        My Tasks
                    </h2>

                    {{-- Success message --}}
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Filter buttons --}}
                    @if(!$tasks->isEmpty())
                        <div class="flex gap-3 mb-6 justify-center">
                            <button onclick="filterTasks('all')" id="btn-all"
                                class="px-4 py-1 rounded-full text-sm font-medium bg-indigo-600 text-white filter-btn">
                                All
                            </button>
                            <button onclick="filterTasks('incomplete')" id="btn-incomplete"
                                class="px-4 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-600 filter-btn">
                                Incomplete
                            </button>
                            <button onclick="filterTasks('complete')" id="btn-complete"
                                class="px-4 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-600 filter-btn">
                                Completed
                            </button>
                        </div>
                    @endif

                    @if($tasks->isEmpty())
                        <p class="text-center text-gray-500 mb-8">You have no tasks yet.</p>
                    @else
                        <table class="w-full border-collapse mb-8" id="tasksTable">
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
                                        data-completed="{{ $task->is_completed ? 'true' : 'false' }}">
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
                                            {{ $task->due_time ? '@ ' . $task->due_time : '' }}
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

    <script>
        function filterTasks(filter) {
            const rows = document.querySelectorAll('.task-row');
            const buttons = document.querySelectorAll('.filter-btn');

            buttons.forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'text-white');
                btn.classList.add('border', 'border-gray-300', 'text-gray-600');
            });

            document.getElementById('btn-' + filter).classList.add('bg-indigo-600', 'text-white');
            document.getElementById('btn-' + filter).classList.remove('border', 'border-gray-300', 'text-gray-600');

            rows.forEach(row => {
                const completed = row.dataset.completed === 'true';
                if (filter === 'all') {
                    row.style.display = '';
                } else if (filter === 'complete' && completed) {
                    row.style.display = '';
                } else if (filter === 'incomplete' && !completed) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
