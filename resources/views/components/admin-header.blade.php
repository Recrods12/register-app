@props(['title' => '', 'active' => ''])

<header class="glass-nav sticky top-0 z-40 animate-fade-in-down">
    <div class="shell flex flex-wrap items-center justify-between gap-4 px-4 py-3.5">
        <a href="/" class="flex items-center gap-3 rounded-2xl outline-none focus-visible:ring-2 focus-visible:ring-sky-500">
            <span class="brand-mark flex h-11 w-11 items-center justify-center rounded-2xl text-lg font-black">R</span>
            <span>
                <span class="block text-[11px] font-bold uppercase tracking-[0.3em] text-sky-700">Panel Admin</span>
                <span class="block text-lg font-extrabold leading-tight text-slate-950">{{ $title }}</span>
            </span>
        </a>

        <nav class="flex flex-wrap items-center gap-2" aria-label="Navigasi admin">
            <a href="/" class="nav-link {{ $active === 'dashboard' ? 'is-active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.calendar') }}" class="nav-link {{ $active === 'calendar' ? 'is-active' : '' }}">Kalender</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ $active === 'users' ? 'is-active' : '' }}">Kelola User</a>
            <a href="{{ route('admin.activity-logs') }}" class="nav-link {{ $active === 'logs' ? 'is-active' : '' }}">Audit Log</a>
        </nav>
    </div>
</header>
