<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CatWang' }} — Catat Uang, Atur Masa Depan</title>

    <!-- Theme Detection Script (Prevents FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('catwang_theme');
            const userTheme = '{{ auth()->user()->theme ?? "light" }}';
            const theme = savedTheme || userTheme;
            if (theme === 'dark' || (!savedTheme && userTheme === 'dark') || (!savedTheme && !userTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    
    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F7F2] dark:bg-[#121214] text-[#121212] dark:text-[#F3F4F6] font-sans min-h-[100dvh] flex flex-col selection:bg-[#FFEB3B] selection:text-black transition-colors duration-150" 
      x-data="{ 
          mobileNavOpen: false,
          isDark: document.documentElement.classList.contains('dark'),
          toggleTheme() {
              this.isDark = !this.isDark;
              if (this.isDark) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('catwang_theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('catwang_theme', 'light');
              }
              window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: this.isDark } }));
              fetch('{{ route('profile.theme.update') }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}',
                      'Accept': 'application/json'
                  },
                  body: JSON.stringify({ theme: this.isDark ? 'dark' : 'light' })
              }).catch(() => {});
          }
      }">

    <!-- Topbar Navigation -->
    <header class="sticky top-0 z-40 bg-white dark:bg-[#18181B] border-b-2 border-black dark:border-zinc-800 shadow-[0_3px_0_#000] transition-colors">
        <div class="max-w-[1600px] 2xl:max-w-[1720px] w-full mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 h-16 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <button @click="mobileNavOpen = !mobileNavOpen" class="md:hidden neo-btn-sm bg-[#FFEB3B] p-2" aria-label="Menu">
                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 sm:gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="CatWang Logo" class="h-8 sm:h-9 md:h-10 w-auto object-contain group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">
                    <span class="hidden sm:inline-block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest border-l-2 border-black dark:border-zinc-700 pl-3">Keuangan Pribadi</span>
                </a>
            </div>

            <!-- Quick Actions, Theme Toggle & User Profile -->
            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Theme Toggle Button -->
                <button @click="toggleTheme()" 
                        class="neo-btn-sm bg-white dark:bg-[#27272A] hover:bg-yellow-100 dark:hover:bg-zinc-700 p-2 text-black dark:text-yellow-400" 
                        :title="isDark ? 'Ubah ke Mode Terang' : 'Ubah ke Mode Gelap'"
                        aria-label="Toggle Theme">
                    <!-- Sun icon for Dark Mode -->
                    <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon icon for Light Mode -->
                    <svg x-show="!isDark" class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.03 9.03 0 008.354-5.646z" />
                    </svg>
                </button>

                <a href="{{ route('transactions.create') }}" class="neo-btn bg-[#FFEB3B] text-black hover:bg-[#fff066] text-xs sm:text-sm px-2.5 sm:px-4 py-2">
                    <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="hidden sm:inline">+ Catat Transaksi</span>
                    <span class="sm:hidden">Catat</span>
                </a>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="neo-btn-sm bg-white dark:bg-[#27272A] hover:bg-[#FFF9C4] dark:hover:bg-zinc-700 flex items-center gap-2 p-1.5 sm:px-3 sm:py-1.5">
                        @if(auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-7 h-7 object-cover rounded-none border-2 border-black">
                        @else
                            <div class="w-7 h-7 bg-[#2196F3] text-white border-2 border-black rounded-none flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="font-bold text-sm hidden md:inline text-black dark:text-white">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="open" @click.outside="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#1E1E24] border-2 border-black rounded-none shadow-[4px_4px_0_#000] p-1.5 z-50 text-black dark:text-white" style="display: none;">
                        <div class="px-3 py-2 border-b-2 border-black dark:border-zinc-700 mb-1 flex items-center gap-2.5">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 object-cover rounded-none border-2 border-black shrink-0">
                            @else
                                <div class="w-8 h-8 bg-[#2196F3] text-white border-2 border-black rounded-none flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-extrabold text-sm truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        <!-- Link Profil & Pengaturan -->
                        <a href="{{ route('profile.edit') }}" 
                           class="w-full text-left font-bold text-sm px-3 py-2 rounded-none hover:bg-[#FFF9C4] dark:hover:bg-zinc-800 transition flex items-center gap-2.5 text-gray-800 dark:text-gray-200">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profil & Pengaturan</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left font-bold text-sm px-3 py-2 rounded-none text-red-600 dark:text-red-400 hover:bg-[#FFEAEA] dark:hover:bg-red-950/40 transition flex items-center gap-2.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span>Keluar (Logout)</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <div class="flex-1 max-w-[1600px] 2xl:max-w-[1720px] w-full mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 py-5 sm:py-6 flex flex-col md:flex-row gap-5 lg:gap-6">

        <!-- Desktop Sidebar -->
        <aside class="hidden md:block w-64 lg:w-72 shrink-0">
            <div class="neo-box p-4 bg-white dark:bg-[#18181B] sticky top-20 flex flex-col justify-between min-h-[calc(100vh-6.5rem)] transition-colors">
                
                <!-- Sidebar Top Section -->
                <div class="space-y-1.5">
                    <div class="px-3 py-1 mb-1">
                        <p class="text-xs font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Navigasi Utama</p>
                    </div>

                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-none font-bold text-sm border-2 transition-all {{ request()->routeIs('dashboard') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-800 hover:border-black' }}">
                        <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('transactions.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-none font-bold text-sm border-2 transition-all {{ request()->routeIs('transactions.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-800 hover:border-black' }}">
                        <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span>Transaksi</span>
                    </a>

                    <a href="{{ route('categories.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-none font-bold text-sm border-2 transition-all {{ request()->routeIs('categories.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-800 hover:border-black' }}">
                        <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>Kategori</span>
                    </a>

                    <a href="{{ route('reports.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-none font-bold text-sm border-2 transition-all {{ request()->routeIs('reports.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-800 hover:border-black' }}">
                        <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Laporan & Ekspor</span>
                    </a>

                    <div class="pt-3 border-t-2 border-black dark:border-zinc-800 my-2">
                        <p class="text-xs font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-wider px-3 mb-2">Rencana Keuangan</p>
                    </div>

                    <a href="{{ route('budgets.index') }}" 
                       class="flex items-center justify-between px-3 py-2.5 rounded-none font-bold text-sm border-2 transition-all {{ request()->routeIs('budgets.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-800 hover:border-black' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Anggaran</span>
                        </div>
                    </a>

                    <a href="{{ route('savings.index') }}" 
                       class="flex items-center justify-between px-3 py-2.5 rounded-none font-bold text-sm border-2 transition-all {{ request()->routeIs('savings.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-800 hover:border-black' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span>Target Tabungan</span>
                        </div>
                    </a>

                    <div class="pt-3 border-t-2 border-black dark:border-zinc-800 my-2">
                        <p class="text-xs font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-wider px-3 mb-2">Akun Saya</p>
                    </div>

                    <!-- Profil & Pengaturan Link in Sidebar -->
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center justify-between px-3 py-2.5 rounded-none font-bold text-sm border-2 transition-all {{ request()->routeIs('profile.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-800 hover:border-black' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profil & Pengaturan</span>
                        </div>
                    </a>
                </div>

                <!-- Sidebar Bottom Section: Tips + Info Badge -->
                <div class="pt-4 space-y-3 mt-auto">
                    <!-- Tips Box Neubrutal -->
                    <div class="p-3 bg-[#E0F2FE] dark:bg-[#1e293b] border-2 border-black rounded-none shadow-[2px_2px_0_#000]">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-blue-900 dark:text-blue-300 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <span class="font-black text-xs uppercase tracking-wide text-blue-950 dark:text-blue-200">Tips Finansial</span>
                        </div>
                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 leading-relaxed">
                            Sisihkan minimal 20% pemasukan untuk tabungan & dana darurat sebelum belanja konsumtif.
                        </p>
                    </div>

                    <!-- System Status Badge -->
                    <div class="px-3 py-2 bg-[#F8F7F2] dark:bg-[#27272A] border-2 border-black dark:border-zinc-700 rounded-none flex items-center justify-between text-xs font-bold text-gray-600 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-none bg-emerald-500 border border-black animate-pulse"></span>
                            <span class="text-[11px] font-black uppercase tracking-wider">CatWang App</span>
                        </div>
                        <span class="text-[10px] font-black px-1.5 py-0.5 bg-[#FFEB3B] text-black border border-black rounded-none shadow-[1px_1px_0_#000]">v1.0</span>
                    </div>
                </div>

            </div>
        </aside>

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileNavOpen" class="fixed inset-0 z-50 md:hidden" style="display: none;">
            <div @click="mobileNavOpen = false" class="fixed inset-0 bg-black/50"></div>

            <div class="fixed top-0 bottom-0 left-0 w-72 max-w-[85vw] bg-white dark:bg-[#18181B] border-r-3 border-black p-5 shadow-[6px_0_0_#000] overflow-y-auto flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b-2 border-black dark:border-zinc-800 mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="CatWang Logo" class="h-8 w-auto object-contain">
                        <button @click="mobileNavOpen = false" class="neo-btn-sm bg-[#FF5252] text-white p-1.5" aria-label="Close menu">
                            <svg class="w-5 h-5 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ route('dashboard') }}" @click="mobileNavOpen = false" 
                           class="flex items-center gap-2.5 font-bold text-sm px-3 py-2.5 rounded-none border-2 {{ request()->routeIs('dashboard') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200' }}">
                            <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('transactions.index') }}" @click="mobileNavOpen = false" 
                           class="flex items-center gap-2.5 font-bold text-sm px-3 py-2.5 rounded-none border-2 {{ request()->routeIs('transactions.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200' }}">
                            <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            Transaksi
                        </a>
                        <a href="{{ route('categories.index') }}" @click="mobileNavOpen = false" 
                           class="flex items-center gap-2.5 font-bold text-sm px-3 py-2.5 rounded-none border-2 {{ request()->routeIs('categories.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200' }}">
                            <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            Kategori
                        </a>
                        <a href="{{ route('reports.index') }}" @click="mobileNavOpen = false" 
                           class="flex items-center gap-2.5 font-bold text-sm px-3 py-2.5 rounded-none border-2 {{ request()->routeIs('reports.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200' }}">
                            <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Laporan & Ekspor
                        </a>
                        <a href="{{ route('budgets.index') }}" @click="mobileNavOpen = false" 
                           class="flex items-center gap-2.5 font-bold text-sm px-3 py-2.5 rounded-none border-2 {{ request()->routeIs('budgets.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200' }}">
                            <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Anggaran
                        </a>
                        <a href="{{ route('savings.index') }}" @click="mobileNavOpen = false" 
                           class="flex items-center gap-2.5 font-bold text-sm px-3 py-2.5 rounded-none border-2 {{ request()->routeIs('savings.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200' }}">
                            <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            Target Tabungan
                        </a>
                        
                        <div class="pt-2 border-t-2 border-black dark:border-zinc-800 my-2"></div>

                        <!-- Link Profil & Pengaturan in Mobile Drawer -->
                        <a href="{{ route('profile.edit') }}" @click="mobileNavOpen = false" 
                           class="flex items-center gap-2.5 font-bold text-sm px-3 py-2.5 rounded-none border-2 {{ request()->routeIs('profile.*') ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-200' }}">
                            <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Profil & Pengaturan
                        </a>
                    </div>
                </div>

                <div class="pt-4 border-t-2 border-black dark:border-zinc-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full neo-btn bg-[#FF5252] text-white py-2.5 text-sm">
                            Keluar (Logout)
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 min-w-0 w-full">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-[#D1FAE5] dark:bg-emerald-950/60 border-2 border-black rounded-none shadow-[4px_4px_0_#000] flex items-center justify-between text-black dark:text-emerald-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-[#10B981] text-white border-2 border-black rounded-none flex items-center justify-center font-black">
                            <svg class="w-5 h-5 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="font-bold text-sm">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="font-black text-lg hover:opacity-75">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-[#FEE2E2] dark:bg-red-950/60 border-2 border-black rounded-none shadow-[4px_4px_0_#000] flex items-center justify-between text-black dark:text-red-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-[#EF4444] text-white border-2 border-black rounded-none flex items-center justify-center font-black">
                            <svg class="w-5 h-5 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <p class="font-bold text-sm">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="font-black text-lg hover:opacity-75">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    <!-- Footer -->
    <footer class="mt-auto border-t-2 border-black dark:border-zinc-800 bg-white dark:bg-[#18181B] py-6 transition-colors">
        <div class="max-w-[1600px] 2xl:max-w-[1720px] w-full mx-auto px-4 sm:px-6 lg:px-8 xl:px-10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="CatWang Logo" class="h-6 w-auto object-contain">
                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">— Catat Uang, Atur Masa Depan.</span>
            </div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                Neubrutalist Finance App &copy; {{ date('Y') }} CatWang. Built with Laravel & Tailwind.
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
