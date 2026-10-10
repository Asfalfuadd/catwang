@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="neo-box p-4 sm:p-6 bg-white dark:bg-[#18181B] dark:border-zinc-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-gray-900 dark:text-white">Halo, {{ auth()->user()->name }}</h1>
            </div>
            <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 mt-1">
                Berikut adalah ringkasan keuangan dan aktivitas Anda untuk bulan {{ now()->isoFormat('MMMM Y') }}.
            </p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('transactions.create') }}" class="neo-btn bg-[#FFEB3B] text-black hover:bg-[#fff066] px-4 sm:px-5 py-2.5 text-xs sm:text-sm w-full sm:w-auto flex items-center justify-center gap-2">
                <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Catat Transaksi Baru</span>
            </a>
        </div>
    </div>

    <!-- 4 Key Stat Cards (No emojis, using SVGs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Saldo Total Card with Eye Toggle -->
        <div class="neo-box p-4 sm:p-5 bg-[#FFEB3B] dark:bg-yellow-400 dark:border-black relative overflow-hidden flex flex-col justify-between min-h-[140px]" 
             x-data="{ 
                 showBalance: localStorage.getItem('catwang_show_balance') !== 'false',
                 toggleShow() {
                     this.showBalance = !this.showBalance;
                     localStorage.setItem('catwang_show_balance', this.showBalance);
                 }
             }">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black uppercase tracking-wider text-black bg-white/90 dark:bg-black dark:text-yellow-400 px-2 py-0.5 border border-black rounded-none shadow-[1px_1px_0_#000]">Total Saldo</span>
                    <!-- Tombol Mata (Show/Hide Balance) -->
                    <button type="button" @click="toggleShow()" 
                            class="p-1 bg-white/90 dark:bg-black dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-zinc-800 border border-black rounded-none shadow-[1px_1px_0_#000] text-black transition-transform active:translate-y-0.5 cursor-pointer" 
                            :title="showBalance ? 'Sembunyikan Saldo' : 'Tampilkan Saldo'"
                            aria-label="Toggle Saldo Visibility">
                        <!-- Eye Open Icon (ketika saldo terlihat) -->
                        <svg x-show="showBalance" class="w-4 h-4 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Eye Slash Icon (ketika saldo disembunyikan) -->
                        <svg x-show="!showBalance" class="w-4 h-4 stroke-[2.2] text-gray-700 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <svg class="w-6 h-6 stroke-[2.2] text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <!-- Saldo Terlihat -->
            <p x-show="showBalance" 
               title="Rp {{ number_format($totalBalance, 0, ',', '.') }}"
               class="text-xl sm:text-2xl lg:text-[1.65rem] xl:text-[1.65rem] 2xl:text-3xl font-black mt-2 tracking-tight truncate {{ $totalBalance < 0 ? 'text-red-700' : 'text-black' }}">
                Rp {{ number_format($totalBalance, 0, ',', '.') }}
            </p>
            <!-- Saldo Disembunyikan (Sensor Mata) -->
            <p x-show="!showBalance" class="text-xl sm:text-2xl lg:text-[1.65rem] xl:text-[1.65rem] 2xl:text-3xl font-black mt-2 tracking-widest text-black select-none" style="display: none;">
                Rp ••••••••
            </p>
            <p class="text-xs font-bold text-gray-900 mt-1">
                Semua akumulasi kas aktif
            </p>
        </div>

        <!-- Pemasukan Bulan Ini -->
        <div class="neo-box p-4 sm:p-5 bg-[#DCFCE7] dark:bg-emerald-950/70 dark:border-emerald-800 flex flex-col justify-between min-h-[140px]">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-green-950 dark:text-emerald-200 bg-white/90 dark:bg-zinc-800 px-2 py-0.5 border border-black dark:border-emerald-700 rounded-none shadow-[1px_1px_0_#000]">Pemasukan</span>
                <svg class="w-6 h-6 text-green-800 dark:text-emerald-300 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <p title="+ Rp {{ number_format($monthIncome, 0, ',', '.') }}"
               class="text-xl sm:text-2xl lg:text-[1.65rem] xl:text-[1.65rem] 2xl:text-3xl font-black mt-2 text-green-900 dark:text-emerald-200 tracking-tight truncate">
                + Rp {{ number_format($monthIncome, 0, ',', '.') }}
            </p>
            <p class="text-xs font-bold text-green-800 dark:text-emerald-300 mt-1">
                Periode {{ now()->isoFormat('MMMM Y') }}
            </p>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="neo-box p-4 sm:p-5 bg-[#FFE4E6] dark:bg-rose-950/70 dark:border-rose-800 flex flex-col justify-between min-h-[140px]">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-red-950 dark:text-rose-200 bg-white/90 dark:bg-zinc-800 px-2 py-0.5 border border-black dark:border-rose-700 rounded-none shadow-[1px_1px_0_#000]">Pengeluaran</span>
                <svg class="w-6 h-6 text-red-800 dark:text-rose-300 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
            </div>
            <p title="- Rp {{ number_format($monthExpense, 0, ',', '.') }}"
               class="text-xl sm:text-2xl lg:text-[1.65rem] xl:text-[1.65rem] 2xl:text-3xl font-black mt-2 text-red-900 dark:text-rose-200 tracking-tight truncate">
                - Rp {{ number_format($monthExpense, 0, ',', '.') }}
            </p>
            <p class="text-xs font-bold text-red-800 dark:text-rose-300 mt-1">
                Periode {{ now()->isoFormat('MMMM Y') }}
            </p>
        </div>

        <!-- Transaksi Bulan Ini -->
        <div class="neo-box p-4 sm:p-5 bg-[#E0F2FE] dark:bg-sky-950/70 dark:border-sky-800 flex flex-col justify-between min-h-[140px]">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-blue-950 dark:text-sky-200 bg-white/90 dark:bg-zinc-800 px-2 py-0.5 border border-black dark:border-sky-700 rounded-none shadow-[1px_1px_0_#000]">Aktivitas</span>
                <svg class="w-6 h-6 text-blue-800 dark:text-sky-300 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <p class="text-xl sm:text-2xl lg:text-[1.65rem] xl:text-[1.65rem] 2xl:text-3xl font-black mt-2 text-blue-950 dark:text-sky-100 tracking-tight truncate">
                {{ $monthTransactionCount }} <span class="text-sm sm:text-base font-bold text-blue-900 dark:text-sky-200">Transaksi</span>
            </p>
            <p class="text-xs font-bold text-blue-800 dark:text-sky-300 mt-1">
                Arus kas tercatat bulan ini
            </p>
        </div>

    </div>

    <!-- Charts Section (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6">
        
        <!-- Monthly Financial Trend (6 Months Bar Chart) -->
        <div class="lg:col-span-7 neo-box p-4 sm:p-5 bg-white dark:bg-[#18181B] dark:border-zinc-700">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b-2 border-black dark:border-zinc-700 mb-4">
                <div>
                    <h2 class="font-black text-base sm:text-lg text-gray-900 dark:text-white">Tren Keuangan 6 Bulan</h2>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Perbandingan pemasukan vs pengeluaran</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-bold text-gray-800 dark:text-gray-200">
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 bg-[#4ADE80] border border-black rounded-none inline-block shadow-[1px_1px_0_#000]"></span> Masuk</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 bg-[#FF5252] border border-black rounded-none inline-block shadow-[1px_1px_0_#000]"></span> Keluar</span>
                </div>
            </div>

            <div class="relative h-64 sm:h-72 lg:h-80 xl:h-88 w-full">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Expense by Category (Doughnut Chart) -->
        <div class="lg:col-span-5 neo-box p-4 sm:p-5 bg-white dark:bg-[#18181B] dark:border-zinc-700">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black dark:border-zinc-700 mb-4">
                <div>
                    <h2 class="font-black text-base sm:text-lg text-gray-900 dark:text-white">Distribusi Pengeluaran</h2>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Bulan {{ now()->isoFormat('MMMM Y') }}</p>
                </div>
                <span class="neo-badge bg-[#FFEB3B] text-black text-[10px]">Kategori</span>
            </div>

            @if(count($categoryChartData) > 0)
                <div class="relative h-64 sm:h-72 lg:h-80 xl:h-88 w-full flex items-center justify-center">
                    <canvas id="categoryExpenseChart"></canvas>
                </div>
            @else
                <div class="h-64 sm:h-72 lg:h-80 xl:h-88 flex flex-col items-center justify-center text-center p-6 bg-[#F8F7F2] dark:bg-zinc-800/50 border-2 border-dashed border-black dark:border-zinc-700 rounded-none">
                    <svg class="w-10 h-10 text-gray-400 stroke-[2] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <p class="font-bold text-sm text-gray-900 dark:text-white">Belum Ada Pengeluaran</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Catat transaksi pengeluaran bulan ini untuk melihat distribusi grafik.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Bottom Section: Recent Transactions & Financial Goals Widget -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6">

        <!-- Recent Transactions List (No emojis) -->
        <div class="lg:col-span-8 neo-box p-4 sm:p-5 bg-white dark:bg-[#18181B] dark:border-zinc-700">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black dark:border-zinc-700 mb-4">
                <div class="flex items-center gap-2">
                    <h2 class="font-black text-base sm:text-lg text-gray-900 dark:text-white">Transaksi Terbaru</h2>
                    <span class="neo-badge bg-[#E0F2FE] dark:bg-sky-900 dark:text-sky-100 text-[10px]">{{ $recentTransactions->count() }} Data</span>
                </div>
                <a href="{{ route('transactions.index') }}" class="neo-btn-sm bg-white dark:bg-zinc-800 dark:text-white hover:bg-[#FFEB3B] dark:hover:bg-[#FFEB3B] dark:hover:text-black text-xs">
                    Lihat Semua Transaksi ➔
                </a>
            </div>

            @if($recentTransactions->count() > 0)
                <div class="space-y-2.5">
                    @foreach($recentTransactions as $tx)
                        <div class="flex items-center justify-between p-3 sm:p-3.5 bg-white dark:bg-[#27272A] border-2 border-black dark:border-zinc-700 rounded-none shadow-[2px_2px_0_#000] hover:bg-[#FFFDF0] dark:hover:bg-zinc-700/80 transition gap-2 sm:gap-4">
                            <div class="flex items-center gap-2.5 sm:gap-3.5 min-w-0">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-none border-2 border-black flex items-center justify-center shrink-0 shadow-[1px_1px_0_#000]"
                                     style="background-color: {{ $tx->category?->color ?? '#E5E7EB' }}">
                                    @if($tx->type === 'income')
                                        <svg class="w-5 h-5 text-black stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    @else
                                        <svg class="w-5 h-5 text-black stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-extrabold text-xs sm:text-sm truncate text-gray-900 dark:text-white">{{ $tx->description }}</p>
                                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mt-0.5">
                                        <span class="text-[10px] sm:text-[11px] font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $tx->transaction_date->isoFormat('D MMMM Y') }}</span>
                                        <span class="text-gray-300 dark:text-zinc-600 hidden xs:inline">•</span>
                                        <span class="text-[10px] sm:text-[11px] font-extrabold uppercase px-1.5 py-0.2 rounded-none border border-black dark:border-zinc-600 bg-gray-100 dark:bg-zinc-800 text-gray-800 dark:text-gray-200 truncate max-w-[120px] sm:max-w-none">
                                            {{ $tx->category?->name ?? 'Tanpa Kategori' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right shrink-0 pl-2 sm:pl-3">
                                <p class="font-black text-xs sm:text-sm md:text-base {{ $tx->type === 'income' ? 'text-green-700 dark:text-emerald-400' : 'text-red-600 dark:text-rose-400' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </p>
                                <span class="text-[9px] sm:text-[10px] font-bold uppercase {{ $tx->type === 'income' ? 'text-green-600 dark:text-emerald-400' : 'text-red-500 dark:text-rose-400' }}">
                                    {{ $tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-[#F8F7F2] dark:bg-zinc-800/50 border-2 border-dashed border-black dark:border-zinc-700 rounded-none">
                    <svg class="w-10 h-10 mx-auto text-gray-400 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <h3 class="font-black text-base mt-2 text-gray-900 dark:text-white">Belum Ada Transaksi</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">Mulai mencatat transaksi pertama Anda sekarang.</p>
                    <a href="{{ route('transactions.create') }}" class="neo-btn bg-[#FFEB3B] text-black text-xs px-4 py-2">
                        + Tambah Transaksi
                    </a>
                </div>
            @endif
        </div>

        <!-- Side Widgets: Budgets & Saving Goals Overview (No emojis) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Budget Widget -->
            <div class="neo-box p-5 bg-white dark:bg-[#18181B] dark:border-zinc-700">
                <div class="flex items-center justify-between pb-3 border-b-2 border-black dark:border-zinc-700 mb-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 stroke-[2.2] text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="font-black text-base text-gray-900 dark:text-white">Pantauan Anggaran</h3>
                    </div>
                    <a href="{{ route('budgets.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">Kelola</a>
                </div>

                @if($budgets->count() > 0)
                    <div class="space-y-4">
                        @foreach($budgets as $b)
                            <div class="p-3 bg-[#F8F7F2] dark:bg-[#27272A] border border-black dark:border-zinc-700 rounded-none">
                                <div class="flex justify-between items-center text-xs font-bold mb-1">
                                    <span class="flex items-center gap-1.5 text-gray-900 dark:text-white">
                                        <span class="w-2.5 h-2.5 rounded-none border border-black" style="background-color: {{ $b->category?->color ?? '#FFEB3B' }}"></span>
                                        {{ $b->category?->name }}
                                    </span>
                                    <span class="{{ $b->spent > $b->amount ? 'text-red-600 dark:text-rose-400 font-black' : 'text-gray-700 dark:text-gray-300' }}">
                                        {{ $b->percentage }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-zinc-700 border border-black dark:border-zinc-600 rounded-none h-2.5 overflow-hidden">
                                    <div class="h-full {{ $b->spent > $b->amount ? 'bg-[#FF5252]' : 'bg-[#FFEB3B]' }} transition-all duration-300"
                                         style="width: {{ $b->percentage }}%"></div>
                                </div>
                                <div class="flex justify-between text-[11px] font-semibold text-gray-500 dark:text-gray-400 mt-1">
                                    <span>Rp {{ number_format($b->spent, 0, ',', '.') }}</span>
                                    <span>Max Rp {{ number_format($b->amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-[#F8F7F2] dark:bg-zinc-800/50 border border-black dark:border-zinc-700 rounded-none text-center text-xs font-semibold text-gray-600 dark:text-gray-400">
                        Belum ada batas anggaran.
                        <a href="{{ route('budgets.index') }}" class="block text-blue-600 dark:text-blue-400 font-bold underline mt-1">+ Buat Anggaran</a>
                    </div>
                @endif
            </div>

            <!-- Saving Goal Widget -->
            <div class="neo-box p-5 bg-[#FFFDF0] dark:bg-[#18181B] dark:border-zinc-700">
                <div class="flex items-center justify-between pb-3 border-b-2 border-black dark:border-zinc-700 mb-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 stroke-[2.2] text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <h3 class="font-black text-base text-gray-900 dark:text-white">Target Tabungan</h3>
                    </div>
                    <a href="{{ route('savings.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
                </div>

                @if($savingGoals->count() > 0)
                    <div class="space-y-3">
                        @foreach($savingGoals as $goal)
                            @php
                                $goalPct = $goal->target_amount > 0 ? min(100, round(($goal->current_amount / $goal->target_amount) * 100)) : 0;
                            @endphp
                            <div class="p-3 bg-white dark:bg-[#27272A] border border-black dark:border-zinc-700 rounded-none shadow-[2px_2px_0_#000]">
                                <div class="flex justify-between text-xs font-bold mb-1">
                                    <span class="truncate text-gray-900 dark:text-white">{{ $goal->name }}</span>
                                    <span class="text-green-700 dark:text-emerald-400">{{ $goalPct }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-zinc-700 border border-black dark:border-zinc-600 rounded-none h-2 overflow-hidden mb-1">
                                    <div class="h-full bg-[#10B981]" style="width: {{ $goalPct }}%"></div>
                                </div>
                                <p class="text-[11px] font-semibold text-gray-600 dark:text-gray-300 text-right">
                                    Rp {{ number_format($goal->current_amount, 0, ',', '.') }} / Rp {{ number_format($goal->target_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-white dark:bg-zinc-800/50 border border-black dark:border-zinc-700 rounded-none text-center text-xs font-semibold text-gray-600 dark:text-gray-400">
                        Belum ada target tabungan aktif.
                        <a href="{{ route('savings.index') }}" class="block text-blue-600 dark:text-blue-400 font-bold underline mt-1">+ Bikin Target Baru</a>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let trendChart = null;
    let categoryChart = null;

    function getThemeColors() {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            isDark: isDark,
            tickColor: isDark ? '#F4F4F5' : '#18181B',
            gridColor: isDark ? '#27272A' : '#E5E7EB',
            tooltipBg: isDark ? '#27272A' : '#000000',
            tooltipBorder: isDark ? '#3F3F46' : '#000000'
        };
    }

    const initialTheme = getThemeColors();

    // 1. Monthly Trend Bar Chart
    const trendCtx = document.getElementById('monthlyTrendChart');
    if (trendCtx) {
        trendChart = new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyTrendLabels) !!},
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: {!! json_encode($monthlyTrendIncome) !!},
                        backgroundColor: '#4ADE80',
                        borderColor: '#000000',
                        borderWidth: 2,
                        borderRadius: 6,
                    },
                    {
                        label: 'Pengeluaran',
                        data: {!! json_encode($monthlyTrendExpense) !!},
                        backgroundColor: '#FF5252',
                        borderColor: '#000000',
                        borderWidth: 2,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: initialTheme.tooltipBg,
                        borderColor: initialTheme.tooltipBorder,
                        borderWidth: 1,
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        titleFont: { weight: 'bold' },
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { 
                            color: initialTheme.tickColor,
                            font: { weight: 'bold', size: 12 } 
                        }
                    },
                    y: {
                        grid: { color: initialTheme.gridColor },
                        ticks: {
                            color: initialTheme.tickColor,
                            font: { weight: 'bold', size: 11 },
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000) + 'jt';
                                if (value >= 1000) return (value / 1000) + 'rb';
                                return value;
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Expense by Category Doughnut Chart
    const categoryCtx = document.getElementById('categoryExpenseChart');
    if (categoryCtx && {!! count($categoryChartData) !!} > 0) {
        categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryChartLabels) !!},
                datasets: [{
                    data: {!! json_encode($categoryChartData) !!},
                    backgroundColor: {!! json_encode($categoryChartColors) !!},
                    borderColor: '#000000',
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: initialTheme.tickColor,
                            font: { weight: 'bold', size: 11 },
                            boxWidth: 14,
                            padding: 12
                        }
                    },
                    tooltip: {
                        backgroundColor: initialTheme.tooltipBg,
                        borderColor: initialTheme.tooltipBorder,
                        borderWidth: 1,
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // Dynamic Chart Re-theming on Dark/Light mode toggle without page reload
    function updateChartsTheme() {
        const theme = getThemeColors();

        if (trendChart) {
            trendChart.options.scales.x.ticks.color = theme.tickColor;
            trendChart.options.scales.y.ticks.color = theme.tickColor;
            trendChart.options.scales.y.grid.color = theme.gridColor;
            if (trendChart.options.plugins && trendChart.options.plugins.tooltip) {
                trendChart.options.plugins.tooltip.backgroundColor = theme.tooltipBg;
                trendChart.options.plugins.tooltip.borderColor = theme.tooltipBorder;
            }
            trendChart.update();
        }

        if (categoryChart) {
            if (categoryChart.options.plugins && categoryChart.options.plugins.legend && categoryChart.options.plugins.legend.labels) {
                categoryChart.options.plugins.legend.labels.color = theme.tickColor;
            }
            if (categoryChart.options.plugins && categoryChart.options.plugins.tooltip) {
                categoryChart.options.plugins.tooltip.backgroundColor = theme.tooltipBg;
                categoryChart.options.plugins.tooltip.borderColor = theme.tooltipBorder;
            }
            categoryChart.update();
        }
    }

    // 1. Listen to explicit window event
    window.addEventListener('theme-changed', updateChartsTheme);

    // 2. Observe changes to documentElement class (MutationObserver) for instant responsiveness
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                updateChartsTheme();
            }
        });
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
});
</script>
@endpush
