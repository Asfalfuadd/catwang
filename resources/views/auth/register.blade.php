<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru — CatWang (Catat Uang)</title>
    <!-- Theme Detection Script -->
    <script>
        if (localStorage.getItem('catwang_theme') === 'dark' || (!('catwang_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFFDF0] dark:bg-[#121214] text-[#121212] dark:text-[#F3F4F6] font-sans min-h-[100dvh] flex flex-col justify-center items-center p-4 selection:bg-[#FFEB3B]">

    <div class="w-full max-w-md">
        <!-- Logo / Brand Header -->
        <div class="text-center mb-8 flex flex-col items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="CatWang Logo" class="h-12 sm:h-14 w-auto object-contain group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">
            </a>
            <p class="text-xs font-bold text-gray-500 mt-2 uppercase tracking-widest">Catat Uang, Atur Masa Depan</p>
        </div>        <!-- Auth Card -->
        <div class="neo-box p-6 sm:p-8 bg-white dark:bg-[#18181B] dark:border-zinc-700 shadow-[6px_6px_0_#000]">
            <div class="mb-6">
                <h2 class="text-2xl font-black text-gray-900 dark:text-white">Buat Akun Baru</h2>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mt-1">Daftar sekarang untuk mulai mencatat pemasukan dan pengeluaran.</p>
            </div>

            @if($errors->any())
                <div class="mb-4 p-3 bg-[#FEE2E2] dark:bg-red-950/70 border-2 border-black dark:border-zinc-700 rounded-none text-xs font-bold text-red-900 dark:text-red-200">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-black uppercase tracking-wider mb-1.5 dark:text-gray-300">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="neo-input" placeholder="Misal: Budi Santoso">
                </div>

                <div>
                    <label for="email" class="block text-xs font-black uppercase tracking-wider mb-1.5 dark:text-gray-300">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="neo-input" placeholder="nama@email.com">
                </div>

                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-xs font-black uppercase tracking-wider mb-1.5 dark:text-gray-300">Kata Sandi (Min. 8 Karakter)</label>
                    <div class="relative">
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                               class="neo-input pr-12 font-medium" placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1.5 text-gray-500 hover:text-black dark:hover:text-white transition-colors cursor-pointer"
                                aria-label="Lihat / Sembunyikan Kata Sandi">
                            <svg x-show="!showPassword" class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div x-data="{ showConfirmPassword: false }">
                    <label for="password_confirmation" class="block text-xs font-black uppercase tracking-wider mb-1.5 dark:text-gray-300">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <input id="password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required
                               class="neo-input pr-12 font-medium" placeholder="••••••••">
                        <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1.5 text-gray-500 hover:text-black dark:hover:text-white transition-colors cursor-pointer"
                                aria-label="Lihat / Sembunyikan Konfirmasi Kata Sandi">
                            <svg x-show="!showConfirmPassword" class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showConfirmPassword" class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full neo-btn bg-[#2196F3] text-white hover:bg-[#1E88E5] py-3 text-base">
                        Daftar Akun CatWang ➔
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t-2 border-black dark:border-zinc-700 text-center text-sm font-semibold text-gray-700 dark:text-gray-400">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-black text-black dark:text-white underline hover:text-[#2196F3] dark:hover:text-blue-400">Masuk Disini</a>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs font-bold text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white">
                ← Kembali ke Halaman Utama
            </a>
        </div>
    </div>

</body>
</html>
