<nav style="border-bottom: 1px solid #ccc; padding: 16px 32px; display: flex; gap: 48px; align-items: center;">
    <a href="{{ url('/') }}">Task Planner</a>
    <a href="{{ route('tasks.index') }}">Dashboard</a>
    <a href="{{ route('tasks.create') }}">New Task</a>
    <span style="margin-left: auto;">{{ Auth::user()->email }}</span>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Log out</button>
    </form>
</nav>