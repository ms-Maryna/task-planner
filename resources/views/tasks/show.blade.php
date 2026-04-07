<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h2 class="text-2xl font-semibold text-gray-800 text-center mb-8">
                        Task Detail
                    </h2>

                    {{-- Title --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Title</label>
                        <div class="w-full border border-gray-200 rounded-md px-4 py-2 bg-gray-50 text-gray-800">
                            {{ $task->title }}
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Description</label>
                        <div class="w-full border border-gray-200 rounded-md px-4 py-2 bg-gray-50 text-gray-800 min-h-[60px]">
                            {{ $task->description ?? '-' }}
                        </div>
                    </div>

                    {{-- Due Date and Time --}}
                    <div class="mb-6 flex gap-6">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Due Date</label>
                            <div class="border border-gray-200 rounded-md px-4 py-2 bg-gray-50 text-gray-800">
                                {{ $task->due_date ? $task->due_date->format('d/m/Y') : '-' }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Time</label>
                            <div class="border border-gray-200 rounded-md px-4 py-2 bg-gray-50 text-gray-800">
                                {{ $task->due_time ?? '-' }}
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                            {{ $task->is_completed
                                ? 'bg-green-100 text-green-700'
                                : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $task->is_completed ? 'Completed' : 'Incomplete' }}
                        </span>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-between">
                        <a href="{{ route('tasks.index') }}"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100">
                            ← Back
                        </a>
                        <a href="{{ route('tasks.edit', $task) }}"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Edit Task
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>