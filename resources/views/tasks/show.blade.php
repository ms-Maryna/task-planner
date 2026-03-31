<x-app-layout>
    <div style="padding: 32px;">
        <h2 style="text-align: center; margin-bottom: 32px;">Task Detail</h2>

        <div style="display: flex; align-items: center; margin-bottom: 24px;">
            <label style="width: 200px;">Title *</label>
            <div style="flex: 1; border: 1px solid #ccc; padding: 12px;">
                {{ $task->title }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 24px;">
            <label style="width: 200px;">Description</label>
            <div style="flex: 1; border: 1px solid #ccc; padding: 12px;">
                {{ $task->description }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 24px;">
            <label style="width: 200px;">Due date</label>
            <div style="border: 1px solid #ccc; padding: 12px; margin-right: 32px;">
                {{ $task->due_date ? $task->due_date->format('d/m/Y') : '-' }}
            </div>
            <label style="margin-right: 16px;">Time</label>
            <div style="border: 1px solid #ccc; padding: 12px;">
                {{ $task->due_time ?? '-' }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 24px;">
            <label style="width: 200px;">Status</label>
            <div style="border: 1px solid #ccc; padding: 12px;">
                {{ $task->is_completed ? 'Complete' : 'Incomplete' }}
            </div>
        </div>
    </div>
</x-app-layout>
