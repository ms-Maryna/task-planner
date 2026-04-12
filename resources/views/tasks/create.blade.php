<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h2 class="text-2xl font-semibold text-gray-800 text-center mb-8">
                        Create New Task
                    </h2>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tasks.store') }}" id="createTaskForm">
                        @csrf

                        {{-- Title --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title"
                                value="{{ old('title') }}"
                                placeholder="e.g. Finish assignment, Buy groceries..."
                                required minlength="3" maxlength="255"
                                class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <span id="titleError" class="text-red-500 text-xs mt-1 hidden">
                                Title must be at least 3 characters.
                            </span>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea name="description" id="description" maxlength="1000" rows="3"
                                placeholder="Add more details about this task (optional)..."
                                class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">{{ old('description') }}</textarea>
                        </div>

                        {{-- Due Date and Time --}}
                        <div class="mb-6 flex gap-6">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Due Date
                                </label>
                                <input type="date" name="due_date" id="due_date"
                                    value="{{ old('due_date') }}"
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                <span id="dateError" class="text-red-500 text-xs mt-1 hidden">
                                    Date cannot be in the past.
                                </span>
                                @error('due_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Time
                                </label>
                                <input type="time" name="due_time"
                                    value="{{ old('due_time') }}"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                @error('due_time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-between mt-8">
                            <a href="{{ route('tasks.index') }}"
                                class="px-6 py-2 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Save Task
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('createTaskForm').addEventListener('submit', function(e) {
            let valid = true;

            const title = document.getElementById('title').value.trim();
            const titleError = document.getElementById('titleError');
            if (title.length < 3) {
                titleError.classList.remove('hidden');
                valid = false;
            } else {
                titleError.classList.add('hidden');
            }

            const dueDate = document.getElementById('due_date').value;
            const dateError = document.getElementById('dateError');
            if (dueDate) {
                const today = new Date().toISOString().split('T')[0];
                if (dueDate < today) {
                    dateError.classList.remove('hidden');
                    valid = false;
                } else {
                    dateError.classList.add('hidden');
                }
            }

            if (!valid) e.preventDefault();
        });
    </script>
</x-app-layout>
