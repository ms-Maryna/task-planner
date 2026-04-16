<x-app-layout>
    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back link --}}
            <a href="{{ $parent ? route('tasks.show', $parent) : route('tasks.index') }}"
                class="inline-flex items-center gap-1.5 text-sm font-medium mb-6 transition-colors duration-150"
                style="color: rgba(255,255,255,0.5);"
                onmouseover="this.style.color='white';"
                onmouseout="this.style.color='rgba(255,255,255,0.5)';">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ $parent ? $parent->title : 'My Tasks' }}
            </a>

            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

                {{-- Card header --}}
                <div class="px-6 py-5" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                            style="background: rgba(255,255,255,0.2);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-white">
                                {{ $parent ? 'Create Subtask' : 'Create New Task' }}
                            </h1>
                            @if($parent)
                                <p class="text-indigo-200 text-xs mt-0.5">Under: {{ $parent->title }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <div class="p-6">
                    @if(session('success'))
                        <div class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium mb-6"
                            style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46;">
                            ✓ {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tasks.store') }}" id="createTaskForm">
                        @csrf

                        @if($parent)
                            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                        @endif

                        {{-- Title --}}
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                Title <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" name="title" id="title"
                                value="{{ old('title') }}"
                                placeholder="e.g. Finish assignment, Buy groceries…"
                                required minlength="3" maxlength="255"
                                class="w-full rounded-xl px-4 py-2.5 text-gray-800 placeholder-gray-300 transition-all duration-150 focus:outline-none"
                                style="border: 1.5px solid #e5e7eb; background:#fafafa;"
                                onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)';"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.background='#fafafa'; this.style.boxShadow='';">
                            <span id="titleError" class="hidden text-xs mt-1" style="color:#ef4444;">
                                Title must be at least 3 characters.
                            </span>
                            @error('title')
                                <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                Description <span class="font-normal normal-case" style="color:#9ca3af;">(optional)</span>
                            </label>
                            <textarea name="description" id="description" maxlength="1000" rows="3"
                                placeholder="Add more details about this task…"
                                class="w-full rounded-xl px-4 py-2.5 text-gray-800 placeholder-gray-300 transition-all duration-150 focus:outline-none resize-none"
                                style="border: 1.5px solid #e5e7eb; background:#fafafa;"
                                onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)';"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.background='#fafafa'; this.style.boxShadow='';">{{ old('description') }}</textarea>
                        </div>

                        {{-- Due Date / Time / Est. Hours --}}
                        <div class="mb-6 grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Due Date</label>
                                <input type="date" name="due_date" id="due_date"
                                    value="{{ old('due_date') }}"
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full rounded-xl px-3 py-2.5 text-gray-700 text-sm transition-all duration-150 focus:outline-none"
                                    style="border: 1.5px solid #e5e7eb; background:#fafafa;"
                                    onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)';"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.background='#fafafa'; this.style.boxShadow='';">
                                <span id="dateError" class="hidden text-xs mt-1" style="color:#ef4444;">Date cannot be in the past.</span>
                                @error('due_date')
                                    <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Time</label>
                                <input type="time" name="due_time"
                                    value="{{ old('due_time') }}"
                                    class="w-full rounded-xl px-3 py-2.5 text-gray-700 text-sm transition-all duration-150 focus:outline-none"
                                    style="border: 1.5px solid #e5e7eb; background:#fafafa;"
                                    onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)';"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.background='#fafafa'; this.style.boxShadow='';">
                                @error('due_time')
                                    <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Est. Hours</label>
                                <input type="number" name="estimated_hours"
                                    value="{{ old('estimated_hours') }}"
                                    placeholder="e.g. 1.5"
                                    min="0.1" max="24" step="any"
                                    class="w-full rounded-xl px-3 py-2.5 text-gray-700 text-sm transition-all duration-150 focus:outline-none"
                                    style="border: 1.5px solid #e5e7eb; background:#fafafa;"
                                    onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)';"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.background='#fafafa'; this.style.boxShadow='';">
                                @error('estimated_hours')
                                    <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex gap-3 pt-4" style="border-top: 1px solid #f3f4f6;">
                            <a href="{{ $parent ? route('tasks.show', $parent) : route('tasks.index') }}"
                                class="flex-1 py-2.5 rounded-xl text-sm font-medium text-center transition-colors duration-150"
                                style="border:1px solid #e5e7eb; color:#4b5563;"
                                onmouseover="this.style.background='#f9fafb';"
                                onmouseout="this.style.background='';">
                                Cancel
                            </a>
                            <button type="submit"
                                class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg active:scale-95"
                                style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
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

            const title      = document.getElementById('title').value.trim();
            const titleError = document.getElementById('titleError');
            if (title.length < 3) {
                titleError.classList.remove('hidden');
                valid = false;
            } else {
                titleError.classList.add('hidden');
            }

            const dueDate  = document.getElementById('due_date').value;
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
