<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-sky-600">Panel Admin</p>
                <h1 class="text-2xl font-black text-slate-900">Kelola User</h1>
            </div>
            <nav class="flex flex-wrap gap-3">
                <a href="/" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-bold text-slate-700 hover:bg-slate-50">Dashboard</a>
                <a href="{{ route('admin.calendar') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-bold text-slate-700 hover:bg-slate-50">Kalender</a>
                <a href="{{ route('admin.activity-logs') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-bold text-slate-700 hover:bg-slate-50">Audit Log</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @if(session('success'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_45px_rgba(15,23,42,0.06)]">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="text-2xl font-black text-slate-900">Database Pengguna</h2>
                    <p class="mt-1 text-sm text-slate-600">Admin dapat mengubah nama, email, role, dan reset password user.</p>
                </div>
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3">
                    <input type="text" name="search" value="{{ $search }}" class="form-input min-w-[260px]" placeholder="Cari nama, email, role...">
                    <button class="rounded-xl bg-sky-600 px-5 py-3 font-bold text-white hover:bg-sky-700">Cari</button>
                </form>
            </div>

            <div class="mt-6 space-y-4">
                @foreach($users as $user)
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        @csrf
                        @method('PUT')

                        <div class="grid gap-4 lg:grid-cols-[1fr_1fr_170px_1fr]">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Role</label>
                                <select name="role" class="form-input">
                                    <option value="user" @selected($user->role === 'user')>User</option>
                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Total Booking</label>
                                <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 font-black text-slate-900">{{ $user->bookings_count }}</div>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-4 md:grid-cols-[1fr_1fr_auto] md:items-end">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Password Baru</label>
                                <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak diganti">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password baru">
                            </div>
                            <button class="rounded-xl bg-slate-900 px-5 py-3 font-bold text-white hover:bg-slate-800">
                                Simpan User
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>
        </section>
    </main>
</body>
</html>
