<nav class="bg-white border-b border-gray-200 px-8 py-4 flex gap-8 items-center">
    <a href="{{ url('/') }}"
        class="text-lg font-bold text-indigo-600 hover:text-indigo-800">
        Task Planner
    </a>

    <a href="{{ route('tasks.index') }}"
        class="text-sm font-medium {{ request()->routeIs('tasks.index') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-gray-600 hover:text-indigo-600' }}">
        Dashboard
    </a>

    <a href="{{ route('tasks.create') }}"
        class="text-sm font-medium {{ request()->routeIs('tasks.create') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-gray-600 hover:text-indigo-600' }}">
        New Task
    </a>

    <span class="ml-auto text-sm text-gray-500">{{ Auth::user()->email }}</span>

    <a href="{{ route('profile.edit') }}"
        class="text-sm text-gray-600 hover:text-indigo-600 font-medium">
        Profile
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="text-sm text-gray-600 hover:text-red-600 font-medium">
            Log out
        </button>
    </form>
</nav>