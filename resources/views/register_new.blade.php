<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gradient-to-br from-sky-600 to-sky-800 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-sky-600 to-sky-700 px-8 py-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-white text-sky-600 text-3xl font-black">
                        🏢
                    </div>
                </div>
                <h1 class="text-3xl font-black text-white">Booking Ruang Rapat</h1>
                <p class="text-sky-100 mt-2">Daftar akun baru</p>
            </div>

            <!-- Form -->
            <div class="px-8 py-8">
                <h2 class="text-2xl font-black text-slate-900 mb-2">Buat Akun Baru</h2>
                <p class="text-slate-600 text-sm mb-6">Isi data untuk mendaftar</p>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-600">❌ {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="/register" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-900 mb-2">
                            👤 Nama Lengkap
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Masukkan nama Anda"
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:border-sky-600 focus:ring-2 focus:ring-sky-100"
                        />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-900 mb-2">
                            📧 Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="nama@email.com"
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:border-sky-600 focus:ring-2 focus:ring-sky-100"
                        />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">
                            🔐 Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Min. 8 karakter"
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:border-sky-600 focus:ring-2 focus:ring-sky-100"
                        />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-900 mb-2">
                            🔐 Konfirmasi Password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            placeholder="Ulangi password"
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:border-sky-600 focus:ring-2 focus:ring-sky-100"
                        />
                    </div>

                    <!-- Register Button -->
                    <button
                        type="submit"
                        class="w-full px-6 py-3 rounded-lg bg-gradient-to-r from-sky-600 to-sky-700 text-white font-bold hover:shadow-lg transition mt-6"
                    >
                        Daftar Sekarang
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center gap-4 my-6">
                    <div class="flex-1 h-px bg-slate-300"></div>
                    <span class="text-xs text-slate-500">ATAU</span>
                    <div class="flex-1 h-px bg-slate-300"></div>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-slate-600 text-sm">
                        Sudah punya akun?
                        <a href="/login" class="font-bold text-sky-600 hover:text-sky-700">
                            Login di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-sky-100 text-sm">
            <p>🔒 Sistem Booking Ruang Rapat v1.0</p>
        </div>
    </div>
</body>
</html>
