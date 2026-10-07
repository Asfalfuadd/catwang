<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CatWang — Catat Uang, Atur Masa Depan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFFDF0] text-[#121212] font-sans selection:bg-[#FFEB3B] selection:text-black min-h-[100dvh] flex flex-col">

    <!-- Navbar -->
    <header class="border-b-3 border-black bg-white sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="CatWang Logo" class="h-10 sm:h-12 w-auto object-contain group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">
            </a>

            <div class="flex items-center gap-3 sm:gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="neo-btn bg-[#FFEB3B] px-4 py-2 text-sm">
                        Ke Dashboard ➔
                    </a>
                @else
                    <a href="{{ route('login') }}" class="font-bold text-sm px-4 py-2 hover:underline">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="neo-btn bg-[#FFEB3B] px-4 py-2 text-sm">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="py-12 sm:py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 bg-[#FFEB3B] border-2 border-black rounded-lg px-3 py-1 shadow-[3px_3px_0_#000]">
                    <span class="text-sm font-black uppercase tracking-wider">Aplikasi Finansial Pribadi Modern</span>
                </div>

                <h1 class="text-4xl sm:text-6xl font-black tracking-tight leading-tight">
                    Catat Uang, <br>
                    <span class="bg-[#FF5252] text-white px-2 border-2 border-black shadow-[4px_4px_0_#000] inline-block mt-1">Atur Masa Depan.</span>
                </h1>

                <p class="text-lg sm:text-xl font-medium text-gray-800 max-w-2xl leading-relaxed">
                    Aplikasi manajemen keuangan pribadi yang cepat, modern, dan visual. Pantau saldo, ketahui kemana perginya uang Anda, dan kendalikan pengeluaran harian dengan desain Neubrutalism yang ceria.
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ route('register') }}" class="neo-btn bg-[#FFEB3B] hover:bg-[#fff066] text-lg px-6 py-3.5 shadow-[5px_5px_0_#000]">
                        Mulai Sekarang — 100% Gratis ➔
                    </a>
                    <a href="{{ route('login') }}" class="neo-btn bg-white hover:bg-gray-100 text-lg px-6 py-3.5 shadow-[5px_5px_0_#000]">
                        Coba Akun Demo
                    </a>
                </div>

                <!-- Neubrutal Value Highlights -->
                <div class="grid grid-cols-3 gap-3 pt-4 max-w-lg">
                    <div class="p-3 bg-white border-2 border-black rounded-lg shadow-[2px_2px_0_#000] text-center">
                        <p class="font-black text-xl">100%</p>
                        <p class="text-xs font-bold text-gray-600">Gratis & Mandiri</p>
                    </div>
                    <div class="p-3 bg-[#E0F2FE] border-2 border-black rounded-lg shadow-[2px_2px_0_#000] text-center">
                        <p class="font-black text-xl">3 Detik</p>
                        <p class="text-xs font-bold text-gray-600">Catat Transaksi</p>
                    </div>
                    <div class="p-3 bg-[#DCFCE7] border-2 border-black rounded-lg shadow-[2px_2px_0_#000] text-center">
                        <p class="font-black text-xl">Privat</p>
                        <p class="text-xs font-bold text-gray-600">Data Terisolasi</p>
                    </div>
                </div>
            </div>

            <!-- Visual Dashboard Preview Card (Neubrutal, No emojis) -->
            <div class="lg:col-span-5">
                <div class="neo-box p-5 bg-white shadow-[8px_8px_0_#000] relative">
                    <!-- Top Bar Window Header -->
                    <div class="flex items-center justify-between pb-3 border-b-2 border-black mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#FF5252] border border-black inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-[#FFEB3B] border border-black inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-[#4CAF50] border border-black inline-block"></span>
                        </div>
                        <span class="font-black text-xs uppercase tracking-wider bg-black text-white px-2 py-0.5 rounded">Dashboard Live Preview</span>
                    </div>

                    <!-- Saldo Mock Card -->
                    <div class="p-4 bg-[#FFEB3B] border-2 border-black rounded-xl shadow-[4px_4px_0_#000] mb-4">
                        <p class="text-xs font-black uppercase tracking-wider text-black">Saldo Bersih Aktif</p>
                        <p class="text-3xl font-black mt-1">Rp 9.873.000</p>
                        <div class="flex items-center gap-2 mt-2 text-xs font-bold text-gray-800">
                            <span class="bg-black text-white px-1.5 py-0.5 rounded text-[11px]">+23.5%</span>
                            <span>dari bulan lalu</span>
                        </div>
                    </div>

                    <!-- Income vs Expense Grid -->
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="p-3 bg-[#DCFCE7] border-2 border-black rounded-lg shadow-[3px_3px_0_#000]">
                            <p class="text-xs font-bold text-green-900 uppercase">Pemasukan</p>
                            <p class="text-lg font-black text-green-950 mt-0.5">+ Rp 11.000.000</p>
                        </div>
                        <div class="p-3 bg-[#FFE4E6] border-2 border-black rounded-lg shadow-[3px_3px_0_#000]">
                            <p class="text-xs font-bold text-red-900 uppercase">Pengeluaran</p>
                            <p class="text-lg font-black text-red-950 mt-0.5">- Rp 1.127.000</p>
                        </div>
                    </div>

                    <!-- Recent List Mock (No emojis) -->
                    <div class="space-y-2 border-2 border-black p-3 rounded-xl bg-[#F8F7F2]">
                        <p class="text-xs font-extrabold uppercase text-gray-500 mb-1">Transaksi Terkini</p>
                        
                        <div class="flex items-center justify-between p-2 bg-white border border-black rounded shadow-[2px_2px_0_#000] text-xs font-bold">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 bg-[#FFEB3B] border border-black rounded flex items-center justify-center font-bold text-[10px]">
                                    EXP
                                </span>
                                <span>Makan Siang Padang</span>
                            </div>
                            <span class="text-red-600 font-extrabold">- Rp 35.000</span>
                        </div>

                        <div class="flex items-center justify-between p-2 bg-white border border-black rounded shadow-[2px_2px_0_#000] text-xs font-bold">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 bg-[#4ADE80] border border-black rounded flex items-center justify-center font-bold text-[10px]">
                                    INC
                                </span>
                                <span>Gaji Bulanan</span>
                            </div>
                            <span class="text-green-700 font-extrabold">+ Rp 8.500.000</span>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('login') }}" class="neo-btn-sm w-full bg-[#2196F3] text-white py-2 shadow-[3px_3px_0_#000]">
                            Eksplorasi Fitur CatWang ➔
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Key Features Section (Zig-zag, No emojis) -->
    <section class="py-16 bg-white border-y-3 border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="neo-badge bg-[#FFEB3B] mb-2">Semua Yang Anda Butuhkan</span>
                <h2 class="text-3xl sm:text-4xl font-black">Fitur Inti Sesuai Kebutuhan Nyata</h2>
                <p class="text-gray-700 font-medium mt-2">Didesain khusus untuk mahasiswa, freelancer, karyawan, dan siapapun yang ingin keuangan teratur.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1 -->
                <div class="neo-box p-6 bg-[#FEF08A] hover:-translate-y-1 transition-transform">
                    <div class="w-12 h-12 bg-white border-2 border-black rounded-xl shadow-[3px_3px_0_#000] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-black text-xl mb-2">Pencatatan Cepat</h3>
                    <p class="text-sm font-medium text-gray-800 leading-relaxed">
                        Tambah pemasukan atau pengeluaran dalam hitungan detik dengan pemilihan kategori otomatis dan nominal praktis.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="neo-box p-6 bg-[#BAE6FD] hover:-translate-y-1 transition-transform">
                    <div class="w-12 h-12 bg-white border-2 border-black rounded-xl shadow-[3px_3px_0_#000] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="font-black text-xl mb-2">Visual & Chart.js</h3>
                    <p class="text-sm font-medium text-gray-800 leading-relaxed">
                        Grafik tren keuangan 6 bulan dan diagram distribusi pengeluaran per kategori yang mudah dipahami sekilas mata.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="neo-box p-6 bg-[#FECDD3] hover:-translate-y-1 transition-transform">
                    <div class="w-12 h-12 bg-white border-2 border-black rounded-xl shadow-[3px_3px_0_#000] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-black text-xl mb-2">Anggaran & Target</h3>
                    <p class="text-sm font-medium text-gray-800 leading-relaxed">
                        Atur batas anggaran bulanan per kategori dan pantau progres pencapaian target tabungan impian Anda.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="neo-box p-6 bg-[#BBF7D0] hover:-translate-y-1 transition-transform">
                    <div class="w-12 h-12 bg-white border-2 border-black rounded-xl shadow-[3px_3px_0_#000] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="font-black text-xl mb-2">Laporan & Ekspor CSV</h3>
                    <p class="text-sm font-medium text-gray-800 leading-relaxed">
                        Bandingkan kinerja keuangan antarbulan dan ekspor riwayat transaksi ke format CSV langsung siap buka di Excel.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Bottom CTA -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto w-full text-center">
        <div class="neo-box p-8 sm:p-12 bg-[#FFEB3B] shadow-[8px_8px_0_#000]">
            <h2 class="text-3xl sm:text-5xl font-black mb-4">Mulai Atur Keuangan Hari Ini!</h2>
            <p class="text-base sm:text-lg font-bold text-gray-800 max-w-xl mx-auto mb-8">
                Jangan biarkan uang mengalir tanpa tahu kemana perginya. Catat, pantau, dan capai target keuangan Anda bersama CatWang.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}" class="neo-btn bg-black text-white hover:bg-gray-800 text-lg px-8 py-3.5 shadow-[4px_4px_0_#fff]">
                    Daftar Sekarang (Gratis) ➔
                </a>
                <a href="{{ route('login') }}" class="neo-btn bg-white text-black hover:bg-gray-100 text-lg px-8 py-3.5">
                    Masuk Akun
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto border-t-3 border-black bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="CatWang Logo" class="h-7 w-auto object-contain">
                <span class="text-xs text-gray-600 font-bold">— Catat Uang, Atur Masa Depan.</span>
            </div>
            <p class="text-xs font-bold text-gray-500">
                Neubrutalist Web App &copy; {{ date('Y') }} CatWang.
            </p>
        </div>
    </footer>

</body>
</html>
