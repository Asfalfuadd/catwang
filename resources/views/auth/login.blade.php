<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — CatWang (Catat Uang)</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFFDF0] text-[#121212] font-sans min-h-[100dvh] flex flex-col justify-center items-center p-4 selection:bg-[#FFEB3B]">

    <div class="w-full max-w-md">
        <!-- Logo / Brand Header -->
        <div class="text-center mb-8 flex flex-col items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="CatWang Logo" class="h-12 sm:h-14 w-auto object-contain group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">
            </a>
            <p class="text-xs font-bold text-gray-500 mt-2 uppercase tracking-widest">Catat Uang, Atur Masa Depan</p>
        </div>

        <!-- Auth Card -->
        <div class="neo-box p-6 sm:p-8 bg-white shadow-[6px_6px_0_#000]">
            <div class="mb-6">
                <h2 class="text-2xl font-black">Selamat Datang Kembali</h2>
                <p class="text-sm font-medium text-gray-600 mt-1">Masuk ke akun CatWang Anda untuk mengelola keuangan.</p>
            </div>

            <!-- Demo Account Quick Fill Helper -->
            <div class="mb-6 p-3.5 bg-[#FFF9C4] border-2 border-black rounded-xl shadow-[3px_3px_0_#000]" x-data>
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black uppercase tracking-wider bg-[#FFEB3B] px-1.5 py-0.5 border border-black rounded">Demo Akun Tersedia</span>
                        <p class="text-xs font-bold text-gray-800 mt-1">demo@catwang.com / password123</p>
                    </div>
                    <button type="button" 
                            @click="document.getElementById('email').value='demo@catwang.com'; document.getElementById('password').value='password123';"
                            class="neo-btn-sm bg-white hover:bg-black hover:text-white text-xs px-2.5 py-1">
                        Isi Otomatis
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-[#D1FAE5] border-2 border-black rounded-lg text-xs font-bold text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-[#FEE2E2] border-2 border-black rounded-lg text-xs font-bold text-red-900">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-black uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="neo-input" placeholder="nama@email.com">
                </div>

                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-xs font-black uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                               class="neo-input pr-12 font-medium" placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1.5 text-gray-500 hover:text-black transition-colors cursor-pointer"
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

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 cursor-pointer font-bold select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 border-2 border-black rounded accent-[#FFEB3B]">
                        <span>Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full neo-btn bg-[#FFEB3B] hover:bg-[#ffe600] py-3 text-base">
                    Masuk ke Dashboard ➔
                </button>
            </form>

            <div class="mt-6 pt-5 border-t-2 border-black text-center text-sm font-semibold text-gray-700">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-black text-black underline hover:text-[#2196F3]">Daftar Sekarang</a>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs font-bold text-gray-500 hover:text-black">
                ← Kembali ke Halaman Utama
            </a>
        </div>
    </div>

</body>
</html>
