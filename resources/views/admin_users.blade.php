<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="page-wrap">
    <x-admin-header title="Kelola User" active="users" />

    <main id="main" class="shell animate-fade-in px-4 py-8">
        @if(session('success'))
            <x-toast type="success" :message="session('success')" />
        @endif

        @if($errors->any())
            <x-toast type="error" :message="implode(' ', $errors->all())" />
        @endif

        @php
            $totalUsers = $users->count();
            $adminCount = $users->where('role', 'admin')->count();
            $regularCount = $totalUsers - $adminCount;
        @endphp

        <section class="hero-card animate-scale-in rounded-[34px] p-6 md:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="eyebrow">Manajemen Akun</span>
                    <h2 class="section-title mt-4">Database Pengguna</h2>
                    <p class="section-copy mt-2">Ubah nama, email, peran, dan reset password pengguna dari satu tempat.</p>
                </div>

                <form method="GET" action="{{ route('admin.users.index') }}" class="flex w-full gap-3 lg:w-auto">
                    <label for="search" class="sr-only">Cari pengguna</label>
                    <input id="search" type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, role..." class="form-input min-w-[240px] flex-1">
                    <button type="submit" class="btn-primary whitespace-nowrap">Cari</button>
                </form>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                <article class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Total Pengguna</p>
                    <p class="mt-3 text-3xl font-black text-slate-950">{{ $totalUsers }}</p>
                </article>
                <article class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Admin</p>
                    <p class="mt-3 text-3xl font-black text-slate-950">{{ $adminCount }}</p>
                </article>
                <article class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">User</p>
                    <p class="mt-3 text-3xl font-black text-slate-950">{{ $regularCount }}</p>
                </article>
            </div>
        </section>

        @if($users->isEmpty())
            <div class="mt-6">
                <x-empty-state
                    icon="👥"
                    title="Tidak ada pengguna ditemukan"
                    description="{{ $search ? 'Coba kata kunci pencarian yang berbeda.' : 'Belum ada pengguna terdaftar dalam sistem.' }}" />
            </div>
        @else
            <div class="stagger mt-6 space-y-4" data-stagger>
                @foreach($users as $user)
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" data-loading-on-submit class="feature-card rounded-3xl p-5 transition duration-300 hover:shadow-[0_22px_50px_rgba(15,23,42,0.10)]">
                        @csrf
                        @method('PUT')

                        <div class="flex flex-wrap items-center gap-4 border-b border-slate-200/70 pb-4">
                            <x-avatar :name="$user->name" size="md" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-lg font-bold text-slate-950">{{ $user->name }}</p>
                                <p class="truncate text-sm text-slate-500">{{ $user->email }}</p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $user->role === 'admin' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                            </span>
                            <span class="rounded-full bg-white/80 px-3 py-1 text-xs font-bold text-slate-600">{{ $user->bookings_count }} booking</span>
                        </div>

                        <div class="mt-4 grid gap-4 lg:grid-cols-[1fr_1fr_170px]">
                            <div>
                                <label for="name-{{ $user->id }}" class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                                <input id="name-{{ $user->id }}" type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input">
                            </div>
                            <div>
                                <label for="email-{{ $user->id }}" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                                <input id="email-{{ $user->id }}" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input">
                            </div>
                            <div>
                                <label for="role-{{ $user->id }}" class="mb-2 block text-sm font-semibold text-slate-700">Role</label>
                                <select id="role-{{ $user->id }}" name="role" class="form-input">
                                    <option value="user" @selected($user->role === 'user')>User</option>
                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-4 md:grid-cols-[1fr_1fr_auto] md:items-end">
                            <div>
                                <label for="password-{{ $user->id }}" class="mb-2 block text-sm font-semibold text-slate-700">Password Baru</label>
                                <input id="password-{{ $user->id }}" type="password" name="password" autocomplete="new-password" class="form-input" placeholder="Kosongkan jika tidak diganti">
                            </div>
                            <div>
                                <label for="password_confirmation-{{ $user->id }}" class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                                <input id="password_confirmation-{{ $user->id }}" type="password" name="password_confirmation" autocomplete="new-password" class="form-input" placeholder="Ulangi password baru">
                            </div>
                            <button type="submit" class="btn-primary whitespace-nowrap">Simpan User</button>
                        </div>
                    </form>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
