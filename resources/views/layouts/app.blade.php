<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Booking Ruang Rapat'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="@yield('body-class', 'bg-slate-50')">
    @if(View::hasSection('header'))
        @yield('header')
    @else
        <header class="site-header sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
            <div class="site-container flex flex-wrap items-center justify-between gap-3 px-4 py-4">
                <a href="/" class="group inline-flex items-center gap-3 text-current no-underline">
                    <span class="brand-mark flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-sky-600 to-sky-700 text-lg font-black text-white shadow-[0_16px_34px_rgba(14,116,144,0.26)]">R</span>
                    <div>
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-500">Booking Ruang Rapat</p>
                        <h1 class="text-lg font-black text-slate-950">Aplikasi Reservasi</h1>
                    </div>
                </a>

                <nav class="flex flex-wrap items-center gap-3 text-sm font-semibold">
                    <a href="/" class="nav-link">Beranda</a>
                    @auth
                        <a href="/bookings" class="nav-link">Booking</a>
                        <a href="/profile" class="nav-link">Profil</a>
                        <a href="/products" class="nav-link">Produk</a>
                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit" class="btn-soft">Logout</button>
                        </form>
                    @else
                        <a href="/login" class="btn-soft">Login</a>
                        <a href="/register" class="btn-primary">Register</a>
                    @endauth
                </nav>
            </div>
        </header>
    @endif

    <main class="site-shell py-10">
        @yield('page-content')
    </main>

    @stack('scripts')
</body>
</html>
