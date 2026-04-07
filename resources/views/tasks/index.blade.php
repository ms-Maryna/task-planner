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
                                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                <input type="checkbox"
                                                    {{ $task->is_completed ? 'checked' : '' }}
                                                    disabled
                                                    class="w-4 h-4">
                                                <span class="{{ $task->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }}">
                                                    {{ $task->title }}
                                                </span>
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
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100">
                            {{ $tasks->isEmpty() ? 'Create your first task' : 'Create new task' }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>