@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="neo-box p-6 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl sm:text-3xl font-black">Halo, {{ auth()->user()->name }}</h1>
            </div>
            <p class="text-sm font-semibold text-gray-600 mt-1">
                Berikut adalah ringkasan keuangan dan aktivitas Anda untuk bulan {{ now()->isoFormat('MMMM Y') }}.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.create') }}" class="neo-btn bg-[#FFEB3B] hover:bg-[#fff066] px-5 py-2.5 text-sm">
                <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Catat Transaksi Baru</span>
            </a>
        </div>
    </div>

    <!-- 4 Key Stat Cards (No emojis, using SVGs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Saldo Total Card -->
        <div class="neo-box p-5 bg-[#FFEB3B] relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-black bg-white px-2 py-0.5 border border-black rounded shadow-[1px_1px_0_#000]">Total Saldo</span>
                <svg class="w-6 h-6 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <p class="text-2xl sm:text-3xl font-black mt-3 truncate {{ $totalBalance < 0 ? 'text-red-700' : 'text-black' }}">
                Rp {{ number_format($totalBalance, 0, ',', '.') }}
            </p>
            <p class="text-xs font-bold text-gray-800 mt-2">
                Semua akumulasi kas aktif
            </p>
        </div>

        <!-- Pemasukan Bulan Ini -->
        <div class="neo-box p-5 bg-[#DCFCE7]">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-green-950 bg-white px-2 py-0.5 border border-black rounded shadow-[1px_1px_0_#000]">Pemasukan</span>
                <svg class="w-6 h-6 text-green-800 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <p class="text-2xl sm:text-3xl font-black mt-3 text-green-900 truncate">
                + Rp {{ number_format($monthIncome, 0, ',', '.') }}
            </p>
            <p class="text-xs font-bold text-green-800 mt-2">
                Periode {{ now()->isoFormat('MMMM Y') }}
            </p>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="neo-box p-5 bg-[#FFE4E6]">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-red-950 bg-white px-2 py-0.5 border border-black rounded shadow-[1px_1px_0_#000]">Pengeluaran</span>
                <svg class="w-6 h-6 text-red-800 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
            </div>
            <p class="text-2xl sm:text-3xl font-black mt-3 text-red-900 truncate">
                - Rp {{ number_format($monthExpense, 0, ',', '.') }}
            </p>
            <p class="text-xs font-bold text-red-800 mt-2">
                Periode {{ now()->isoFormat('MMMM Y') }}
            </p>
        </div>

        <!-- Transaksi Bulan Ini -->
        <div class="neo-box p-5 bg-[#E0F2FE]">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-blue-950 bg-white px-2 py-0.5 border border-black rounded shadow-[1px_1px_0_#000]">Aktivitas</span>
                <svg class="w-6 h-6 text-blue-800 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <p class="text-2xl sm:text-3xl font-black mt-3 text-blue-950">
                {{ $monthTransactionCount }} <span class="text-base font-bold">Transaksi</span>
            </p>
            <p class="text-xs font-bold text-blue-800 mt-2">
                Arus kas tercatat bulan ini
            </p>
        </div>

    </div>

    <!-- Charts Section (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Monthly Financial Trend (6 Months Bar Chart) -->
        <div class="lg:col-span-7 neo-box p-5 bg-white">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black mb-4">
                <div>
                    <h2 class="font-black text-lg">Tren Keuangan 6 Bulan</h2>
                    <p class="text-xs font-semibold text-gray-500">Perbandingan pemasukan vs pengeluaran</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-bold">
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 bg-[#4ADE80] border border-black rounded-sm inline-block"></span> Masuk</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 bg-[#FF5252] border border-black rounded-sm inline-block"></span> Keluar</span>
                </div>
            </div>

            <div class="relative h-64 sm:h-72 w-full">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Expense by Category (Doughnut Chart) -->
        <div class="lg:col-span-5 neo-box p-5 bg-white">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black mb-4">
                <div>
                    <h2 class="font-black text-lg">Distribusi Pengeluaran</h2>
                    <p class="text-xs font-semibold text-gray-500">Bulan {{ now()->isoFormat('MMMM Y') }}</p>
                </div>
                <span class="neo-badge bg-[#FFEB3B] text-[10px]">Kategori</span>
            </div>

            @if(count($categoryChartData) > 0)
                <div class="relative h-64 sm:h-72 w-full flex items-center justify-center">
                    <canvas id="categoryExpenseChart"></canvas>
                </div>
            @else
                <div class="h-64 flex flex-col items-center justify-center text-center p-6 bg-[#F8F7F2] border-2 border-dashed border-black rounded-xl">
                    <svg class="w-10 h-10 text-gray-400 stroke-[2] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <p class="font-bold text-sm">Belum Ada Pengeluaran</p>
                    <p class="text-xs text-gray-500 mt-1">Catat transaksi pengeluaran bulan ini untuk melihat distribusi grafik.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Bottom Section: Recent Transactions & Financial Goals Widget -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Recent Transactions List (No emojis) -->
        <div class="lg:col-span-8 neo-box p-5 bg-white">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black mb-4">
                <div class="flex items-center gap-2">
                    <h2 class="font-black text-lg">Transaksi Terbaru</h2>
                    <span class="neo-badge bg-[#E0F2FE] text-[10px]">{{ $recentTransactions->count() }} Data</span>
                </div>
                <a href="{{ route('transactions.index') }}" class="neo-btn-sm bg-white hover:bg-[#FFEB3B] text-xs">
                    Lihat Semua Transaksi ➔
                </a>
            </div>

            @if($recentTransactions->count() > 0)
                <div class="space-y-2.5">
                    @foreach($recentTransactions as $tx)
                        <div class="flex items-center justify-between p-3 bg-white border-2 border-black rounded-xl shadow-[2px_2px_0_#000] hover:bg-[#FFFDF0] transition">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-lg border-2 border-black flex items-center justify-center shrink-0 shadow-[1px_1px_0_#000]"
                                     style="background-color: {{ $tx->category?->color ?? '#E5E7EB' }}">
                                    @if($tx->type === 'income')
                                        <svg class="w-5 h-5 text-black stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    @else
                                        <svg class="w-5 h-5 text-black stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-extrabold text-sm truncate text-gray-900">{{ $tx->description }}</p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[11px] font-bold text-gray-500">{{ $tx->transaction_date->isoFormat('D MMMM Y') }}</span>
                                        <span class="text-gray-300">•</span>
                                        <span class="text-[11px] font-extrabold uppercase px-1.5 py-0.2 rounded border border-black bg-gray-100">
                                            {{ $tx->category?->name ?? 'Tanpa Kategori' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right shrink-0 pl-3">
                                <p class="font-black text-sm sm:text-base {{ $tx->type === 'income' ? 'text-green-700' : 'text-red-600' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </p>
                                <span class="text-[10px] font-bold uppercase {{ $tx->type === 'income' ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-[#F8F7F2] border-2 border-dashed border-black rounded-xl">
                    <svg class="w-10 h-10 mx-auto text-gray-400 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <h3 class="font-black text-base mt-2">Belum Ada Transaksi</h3>
                    <p class="text-xs text-gray-500 mt-1 mb-4">Mulai mencatat transaksi pertama Anda sekarang.</p>
                    <a href="{{ route('transactions.create') }}" class="neo-btn bg-[#FFEB3B] text-xs px-4 py-2">
                        + Tambah Transaksi
                    </a>
                </div>
            @endif
        </div>

        <!-- Side Widgets: Budgets & Saving Goals Overview (No emojis) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Budget Widget -->
            <div class="neo-box p-5 bg-white">
                <div class="flex items-center justify-between pb-3 border-b-2 border-black mb-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="font-black text-base">Pantauan Anggaran</h3>
                    </div>
                    <a href="{{ route('budgets.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Kelola</a>
                </div>

                @if($budgets->count() > 0)
                    <div class="space-y-4">
                        @foreach($budgets as $b)
                            <div class="p-3 bg-[#F8F7F2] border border-black rounded-lg">
                                <div class="flex justify-between items-center text-xs font-bold mb-1">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full border border-black" style="background-color: {{ $b->category?->color ?? '#FFEB3B' }}"></span>
                                        {{ $b->category?->name }}
                                    </span>
                                    <span class="{{ $b->spent > $b->amount ? 'text-red-600 font-black' : 'text-gray-700' }}">
                                        {{ $b->percentage }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 border border-black rounded-full h-2.5 overflow-hidden">
                                    <div class="h-full {{ $b->spent > $b->amount ? 'bg-[#FF5252]' : 'bg-[#FFEB3B]' }} transition-all duration-300"
                                         style="width: {{ $b->percentage }}%"></div>
                                </div>
                                <div class="flex justify-between text-[11px] font-semibold text-gray-500 mt-1">
                                    <span>Rp {{ number_format($b->spent, 0, ',', '.') }}</span>
                                    <span>Max Rp {{ number_format($b->amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-[#F8F7F2] border border-black rounded-lg text-center text-xs font-semibold text-gray-600">
                        Belum ada batas anggaran.
                        <a href="{{ route('budgets.index') }}" class="block text-blue-600 font-bold underline mt-1">+ Buat Anggaran</a>
                    </div>
                @endif
            </div>

            <!-- Saving Goal Widget -->
            <div class="neo-box p-5 bg-[#FFFDF0]">
                <div class="flex items-center justify-between pb-3 border-b-2 border-black mb-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <h3 class="font-black text-base">Target Tabungan</h3>
                    </div>
                    <a href="{{ route('savings.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua</a>
                </div>

                @if($savingGoals->count() > 0)
                    <div class="space-y-3">
                        @foreach($savingGoals as $goal)
                            @php
                                $goalPct = $goal->target_amount > 0 ? min(100, round(($goal->current_amount / $goal->target_amount) * 100)) : 0;
                            @endphp
                            <div class="p-3 bg-white border border-black rounded-lg shadow-[2px_2px_0_#000]">
                                <div class="flex justify-between text-xs font-bold mb-1">
                                    <span class="truncate">{{ $goal->name }}</span>
                                    <span class="text-green-700">{{ $goalPct }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 border border-black rounded-full h-2 overflow-hidden mb-1">
                                    <div class="h-full bg-[#10B981]" style="width: {{ $goalPct }}%"></div>
                                </div>
                                <p class="text-[11px] font-semibold text-gray-600 text-right">
                                    Rp {{ number_format($goal->current_amount, 0, ',', '.') }} / Rp {{ number_format($goal->target_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-white border border-black rounded-lg text-center text-xs font-semibold text-gray-600">
                        Belum ada target tabungan aktif.
                        <a href="{{ route('savings.index') }}" class="block text-blue-600 font-bold underline mt-1">+ Bikin Target Baru</a>
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
    // 1. Monthly Trend Bar Chart
    const trendCtx = document.getElementById('monthlyTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
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
                        backgroundColor: '#000000',
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
                        ticks: { font: { weight: 'bold' } }
                    },
                    y: {
                        grid: { color: '#E5E7EB' },
                        ticks: {
                            font: { weight: 'bold' },
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
        new Chart(categoryCtx, {
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
                            font: { weight: 'bold', size: 11 },
                            boxWidth: 14,
                            padding: 12
                        }
                    },
                    tooltip: {
                        backgroundColor: '#000000',
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
});
</script>
@endpush
