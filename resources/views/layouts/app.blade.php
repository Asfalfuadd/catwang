<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CatWang' }} — Catat Uang, Atur Masa Depan</title>
    
    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F7F2] text-[#121212] font-sans min-h-[100dvh] flex flex-col selection:bg-[#FFEB3B] selection:text-black" x-data="{ mobileNavOpen: false }">

    <!-- Topbar Navigation -->
    <header class="sticky top-0 z-40 bg-white border-b-2 border-black shadow-[0_3px_0_#000]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <div class="flex items-center gap-3">
                <button @click="mobileNavOpen = !mobileNavOpen" class="md:hidden neo-btn-sm bg-[#FFEB3B] p-2" aria-label="Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="CatWang Logo" class="h-9 sm:h-10 w-auto object-contain group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">
                    <span class="hidden sm:inline-block text-xs font-bold text-gray-500 uppercase tracking-widest border-l-2 border-black pl-3">Keuangan Pribadi</span>
                </a>
            </div>

            <!-- Quick Actions & User Profile -->
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ route('transactions.create') }}" class="neo-btn bg-[#FFEB3B] hover:bg-[#fff066] text-sm px-3 sm:px-4 py-2">
                    <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="hidden sm:inline">+ Catat Transaksi</span>
                    <span class="sm:hidden">Catat</span>
                </a>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="neo-btn-sm bg-white hover:bg-[#FFF9C4] flex items-center gap-2">
                        <div class="w-7 h-7 bg-[#2196F3] text-white border-2 border-black rounded-md flex items-center justify-center font-bold text-xs">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="font-bold text-sm hidden md:inline">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="open" @click.outside="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-48 bg-white border-2 border-black rounded-xl shadow-[4px_4px_0_#000] p-1.5 z-50" style="display: none;">
                        <div class="px-3 py-2 border-b-2 border-black mb-1">
                            <p class="text-xs font-bold text-gray-500 uppercase">Login sebagai:</p>
                            <p class="font-extrabold text-sm truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left font-bold text-sm px-3 py-2 rounded-lg text-red-600 hover:bg-[#FFEAEA] transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar (Logout)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 flex gap-6">

        <!-- Desktop Sidebar -->
        <aside class="hidden md:block w-64 shrink-0">
            <div class="neo-box p-4 bg-white sticky top-24 space-y-1.5">
                <div class="px-3 py-1 mb-2">
                    <p class="text-xs font-extrabold text-gray-400 uppercase tracking-wider">Navigasi Utama</p>
                </div>

                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-bold text-sm border-2 transition-all {{ request()->routeIs('dashboard') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent hover:bg-gray-100 hover:border-black' }}">
                    <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('transactions.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-bold text-sm border-2 transition-all {{ request()->routeIs('transactions.*') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent hover:bg-gray-100 hover:border-black' }}">
                    <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>Transaksi</span>
                </a>

                <a href="{{ route('categories.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-bold text-sm border-2 transition-all {{ request()->routeIs('categories.*') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent hover:bg-gray-100 hover:border-black' }}">
                    <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span>Kategori</span>
                </a>

                <a href="{{ route('reports.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-bold text-sm border-2 transition-all {{ request()->routeIs('reports.*') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent hover:bg-gray-100 hover:border-black' }}">
                    <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Laporan & Ekspor</span>
                </a>

                <div class="pt-3 border-t-2 border-black my-2">
                    <p class="text-xs font-extrabold text-gray-400 uppercase tracking-wider px-3 mb-2">Rencana Keuangan</p>
                </div>

                <a href="{{ route('budgets.index') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-lg font-bold text-sm border-2 transition-all {{ request()->routeIs('budgets.*') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent hover:bg-gray-100 hover:border-black' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Anggaran</span>
                    </div>
                </a>

                <a href="{{ route('savings.index') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-lg font-bold text-sm border-2 transition-all {{ request()->routeIs('savings.*') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent hover:bg-gray-100 hover:border-black' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span>Target Tabungan</span>
                    </div>
                </a>

                <!-- Tips Box Neubrutal (No emojis) -->
                <div class="mt-6 p-3 bg-[#E0F2FE] border-2 border-black rounded-lg shadow-[2px_2px_0_#000]">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-blue-900 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <span class="font-black text-xs uppercase tracking-wide">Tips Finansial</span>
                    </div>
                    <p class="text-xs font-medium text-gray-700 leading-relaxed">
                        Sisihkan minimal 20% pemasukan untuk tabungan & dana darurat sebelum belanja konsumtif.
                    </p>
                </div>
            </div>
        </aside>

        <!-- Mobile Drawer Navigation (No emojis) -->
        <div x-show="mobileNavOpen" class="fixed inset-0 z-50 md:hidden" style="display: none;">
            <div @click="mobileNavOpen = false" class="fixed inset-0 bg-black/50"></div>

            <div class="fixed top-0 bottom-0 left-0 w-72 bg-white border-r-3 border-black p-5 shadow-[6px_0_0_#000] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b-2 border-black mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="CatWang Logo" class="h-8 w-auto object-contain">
                    <button @click="mobileNavOpen = false" class="neo-btn-sm bg-[#FF5252] text-white p-1.5">
                        <svg class="w-5 h-5 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('dashboard') }}" @click="mobileNavOpen = false" 
                       class="flex items-center gap-2.5 font-bold text-sm px-3 py-2 rounded-lg border-2 {{ request()->routeIs('dashboard') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent' }}">
                        <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('transactions.index') }}" @click="mobileNavOpen = false" 
                       class="flex items-center gap-2.5 font-bold text-sm px-3 py-2 rounded-lg border-2 {{ request()->routeIs('transactions.*') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent' }}">
                        <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Transaksi
                    </a>
                    <a href="{{ route('categories.index') }}" @click="mobileNavOpen = false" 
                       class="flex items-center gap-2.5 font-bold text-sm px-3 py-2 rounded-lg border-2 {{ request()->routeIs('categories.*') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent' }}">
                        <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Kategori
                    </a>
                    <a href="{{ route('reports.index') }}" @click="mobileNavOpen = false" 
                       class="flex items-center gap-2.5 font-bold text-sm px-3 py-2 rounded-lg border-2 {{ request()->routeIs('reports.*') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent' }}">
                        <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Laporan & Ekspor
                    </a>
                    <a href="{{ route('budgets.index') }}" @click="mobileNavOpen = false" 
                       class="flex items-center gap-2.5 font-bold text-sm px-3 py-2 rounded-lg border-2 {{ request()->routeIs('budgets.*') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent' }}">
                        <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Anggaran
                    </a>
                    <a href="{{ route('savings.index') }}" @click="mobileNavOpen = false" 
                       class="flex items-center gap-2.5 font-bold text-sm px-3 py-2 rounded-lg border-2 {{ request()->routeIs('savings.*') ? 'bg-[#FFEB3B] border-black shadow-[2px_2px_0_#000]' : 'border-transparent' }}">
                        <svg class="w-4 h-4 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Target Tabungan
                    </a>
                </div>

                <div class="mt-8 pt-4 border-t-2 border-black">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full neo-btn bg-[#FF5252] text-white py-2 text-sm">
                            Keluar (Logout)
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 min-w-0">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-[#D1FAE5] border-2 border-black rounded-xl shadow-[4px_4px_0_#000] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-[#10B981] text-white border-2 border-black rounded-md flex items-center justify-center font-black">
                            <svg class="w-5 h-5 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="font-bold text-sm text-gray-900">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="font-black text-lg hover:opacity-75">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-[#FEE2E2] border-2 border-black rounded-xl shadow-[4px_4px_0_#000] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-[#EF4444] text-white border-2 border-black rounded-md flex items-center justify-center font-black">
                            <svg class="w-5 h-5 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <p class="font-bold text-sm text-gray-900">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="font-black text-lg hover:opacity-75">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    <!-- Footer -->
    <footer class="mt-auto border-t-2 border-black bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="CatWang Logo" class="h-6 w-auto object-contain">
                <span class="text-xs text-gray-600 font-semibold">— Catat Uang, Atur Masa Depan.</span>
            </div>
            <p class="text-xs font-semibold text-gray-500">
                Neubrutalist Finance App &copy; {{ date('Y') }} CatWang. Built with Laravel & Tailwind.
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
