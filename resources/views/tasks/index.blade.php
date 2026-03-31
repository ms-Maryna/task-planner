<x-app-layout>
    <div style="padding: 32px;">
        <h2 style="text-align: center; margin-bottom: 32px;">My Tasks</h2>

        @forelse($tasks as $task)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="text-align: left; padding: 8px;">Title</th>
                        <th style="text-align: left; padding: 8px;">Due</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr style="border-top: 1px solid #ccc;">
                            <td style="padding: 12px 8px; display: flex; align-items: center; gap: 12px;">
                                <input type="checkbox" {{ $task->is_completed ? 'checked' : '' }} disabled>
                                {{ $task->title }}
                            </td>
                            <td style="padding: 12px 8px;">
                                {{ $task->due_date ? $task->due_date->format('d/m/y') : '' }}
                                {{ $task->due_time ? '@ ' . $task->due_time : '' }}
                            </td>
                            <td style="padding: 12px 8px;">
                                <a href="{{ route('tasks.edit', $task) }}">Edit</a>
                            </td>
                            <td style="padding: 12px 8px;">
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @empty
            <p style="text-align: center; margin-bottom: 24px;">You have no tasks yet.</p>
        @endforelse

        <div style="text-align: center; margin-top: 32px;">
            <a href="{{ route('tasks.create') }}" style="border: 1px solid #ccc; padding: 12px 48px;">
                {{ $tasks->isEmpty() ? 'Create your first task' : 'Create new task' }}
            </a>
        </div>
    </div>
</x-app-layout>
