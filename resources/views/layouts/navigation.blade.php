<nav style="background: rgba(15,23,42,0.80); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(99,102,241,0.25);" class="sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-6">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 text-white font-bold text-base tracking-tight flex-shrink-0">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            Task Planner
        </a>

        {{-- Nav links --}}
        <div class="flex items-center gap-1">
            <a href="{{ route('tasks.index') }}"
                class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors duration-150
                    {{ request()->routeIs('tasks.index') ? 'text-white' : 'text-white/50 hover:text-white hover:bg-white/10' }}"
                style="{{ request()->routeIs('tasks.index') ? 'background: rgba(99,102,241,0.3);' : '' }}">
                My Tasks
            </a>
            <a href="{{ route('tasks.create') }}"
                class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors duration-150
                    {{ request()->routeIs('tasks.create') ? 'text-white' : 'text-white/50 hover:text-white hover:bg-white/10' }}"
                style="{{ request()->routeIs('tasks.create') ? 'background: rgba(99,102,241,0.3);' : '' }}">
                + New Task
            </a>
        </div>

        {{-- Right side --}}
        <div class="ml-auto flex items-center gap-4">
            <span class="text-white/30 text-sm hidden md:block truncate max-w-[160px]">{{ Auth::user()->email }}</span>

            <a href="{{ route('profile.edit') }}"
                class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold hover:opacity-80 transition-opacity flex-shrink-0"
                style="background: linear-gradient(135deg, #6366f1, #a855f7);"
                title="Profile">
                {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
            </a>

            <form method="POST" action="{{ route('logout') }}" class="flex">
                @csrf
                <button type="submit"
                    class="text-sm text-white/40 hover:text-red-400 font-medium transition-colors duration-150">
                    Log out
                </button>
            </form>
        </div>
    </div>
</nav>
