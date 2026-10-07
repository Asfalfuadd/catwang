@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Section with Month Picker & CSV Export -->
    <div class="neo-box p-5 sm:p-6 bg-white flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <span class="neo-badge bg-[#FFEB3B] mb-1">Analisis Finansial</span>
            <h1 class="text-2xl sm:text-3xl font-black">Laporan Keuangan Bulanan</h1>
            <p class="text-xs sm:text-sm font-semibold text-gray-600 mt-1">
                Laporan periode: <span class="font-extrabold text-black">{{ $startDate->isoFormat('MMMM Y') }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Period Selector Form -->
            <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
                <select name="month" class="neo-input text-xs py-2 font-bold w-36">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>

                <select name="year" class="neo-input text-xs py-2 font-bold w-24">
                    @for($y = now()->year + 1; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button type="submit" class="neo-btn-sm bg-[#FFEB3B] hover:bg-[#ffe600] px-3 py-2 text-xs">
                    Tampilkan
                </button>
            </form>

            <!-- Export CSV Button -->
            <a href="{{ route('reports.export', ['year' => $year, 'month' => $month]) }}" 
               class="neo-btn bg-[#22C55E] text-black hover:bg-[#16a34a] hover:text-white px-4 py-2 text-xs">
                <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Unduh CSV (Excel)</span>
            </a>
        </div>
    </div>

    <!-- 4 Key Report Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="neo-box p-4 bg-[#DCFCE7]">
            <p class="text-xs font-black uppercase tracking-wider text-green-950">Total Pemasukan</p>
            <p class="text-2xl font-black text-green-950 mt-1 truncate">+ Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
            <div class="flex items-center gap-1.5 mt-2 text-xs font-bold {{ $diffIncome >= 0 ? 'text-green-800' : 'text-red-700' }}">
                <span>{{ $diffIncome >= 0 ? '▲ +' : '▼ -' }} Rp {{ number_format(abs($diffIncome), 0, ',', '.') }}</span>
                <span class="text-gray-500 font-semibold">vs bulan lalu</span>
            </div>
        </div>

        <div class="neo-box p-4 bg-[#FFE4E6]">
            <p class="text-xs font-black uppercase tracking-wider text-red-950">Total Pengeluaran</p>
            <p class="text-2xl font-black text-red-950 mt-1 truncate">- Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
            <div class="flex items-center gap-1.5 mt-2 text-xs font-bold {{ $diffExpense <= 0 ? 'text-green-800' : 'text-red-700' }}">
                <span>{{ $diffExpense >= 0 ? '▲ +' : '▼ -' }} Rp {{ number_format(abs($diffExpense), 0, ',', '.') }}</span>
                <span class="text-gray-500 font-semibold">vs bulan lalu</span>
            </div>
        </div>

        <div class="neo-box p-4 bg-[#FFEB3B]">
            <p class="text-xs font-black uppercase tracking-wider text-black">Saldo Bersih Periode</p>
            <p class="text-2xl font-black {{ $netBalance >= 0 ? 'text-black' : 'text-red-700' }} mt-1 truncate">
                Rp {{ number_format($netBalance, 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-1.5 mt-2 text-xs font-bold {{ $diffBalance >= 0 ? 'text-green-800' : 'text-red-700' }}">
                <span>{{ $diffBalance >= 0 ? '▲ +' : '▼ -' }} Rp {{ number_format(abs($diffBalance), 0, ',', '.') }}</span>
                <span class="text-gray-700 font-semibold">vs bulan lalu</span>
            </div>
        </div>

        <div class="neo-box p-4 bg-[#E0F2FE]">
            <p class="text-xs font-black uppercase tracking-wider text-blue-950">Jumlah Transaksi</p>
            <p class="text-2xl font-black text-blue-950 mt-1">{{ $transactionCount }} <span class="text-sm font-bold">Catatan</span></p>
            <p class="text-xs font-semibold text-blue-800 mt-2">
                Rata-rata pengeluaran: Rp {{ $transactionCount > 0 ? number_format($totalExpense / max(1, $transactions->where('type', 'expense')->count()), 0, ',', '.') : 0 }}
            </p>
        </div>

    </div>

    <!-- Category Expense Breakdown Progress List (No emojis) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Expense by Category -->
        <div class="neo-box p-5 bg-white">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                    <h2 class="font-black text-base">Pengeluaran Berdasarkan Kategori</h2>
                </div>
                <span class="neo-badge bg-[#FFE4E6] text-red-900 text-[10px]">{{ $expenseByCategory->count() }} Kategori</span>
            </div>

            @if($expenseByCategory->count() > 0)
                <div class="space-y-4">
                    @foreach($expenseByCategory as $item)
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-xs font-bold">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full border border-black inline-block" style="background-color: {{ $item['category']?->color ?? '#FF5252' }}"></span>
                                    {{ $item['category']?->name ?? 'Lainnya' }} ({{ $item['count'] }}x)
                                </span>
                                <div>
                                    <span class="font-black text-black">Rp {{ number_format($item['total'], 0, ',', '.') }}</span>
                                    <span class="text-gray-500 ml-1">({{ $item['percentage'] }}%)</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 border border-black rounded-full h-3 overflow-hidden">
                                <div class="h-full border-r border-black" 
                                     style="width: {{ $item['percentage'] }}%; background-color: {{ $item['category']?->color ?? '#FF5252' }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center text-xs font-bold text-gray-500 bg-[#F8F7F2] border border-black rounded-lg">
                    Tidak ada transaksi pengeluaran pada bulan ini.
                </div>
            @endif
        </div>

        <!-- Income by Category -->
        <div class="neo-box p-5 bg-white">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <h2 class="font-black text-base">Pemasukan Berdasarkan Kategori</h2>
                </div>
                <span class="neo-badge bg-[#DCFCE7] text-green-900 text-[10px]">{{ $incomeByCategory->count() }} Kategori</span>
            </div>

            @if($incomeByCategory->count() > 0)
                <div class="space-y-4">
                    @foreach($incomeByCategory as $item)
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-xs font-bold">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full border border-black inline-block" style="background-color: {{ $item['category']?->color ?? '#4CAF50' }}"></span>
                                    {{ $item['category']?->name ?? 'Lainnya' }} ({{ $item['count'] }}x)
                                </span>
                                <div>
                                    <span class="font-black text-green-800">Rp {{ number_format($item['total'], 0, ',', '.') }}</span>
                                    <span class="text-gray-500 ml-1">({{ $item['percentage'] }}%)</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 border border-black rounded-full h-3 overflow-hidden">
                                <div class="h-full border-r border-black bg-[#4ADE80]" 
                                     style="width: {{ $item['percentage'] }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center text-xs font-bold text-gray-500 bg-[#F8F7F2] border border-black rounded-lg">
                    Tidak ada transaksi pemasukan pada bulan ini.
                </div>
            @endif
        </div>

    </div>

    <!-- Period Transactions Breakdown Table -->
    <div class="neo-box bg-white overflow-hidden">
        <div class="p-4 border-b-2 border-black flex items-center justify-between">
            <h2 class="font-black text-base">Detail Catatan Transaksi Periode {{ $startDate->isoFormat('MMMM Y') }}</h2>
            <span class="neo-badge bg-black text-white text-[10px]">{{ $transactions->count() }} Baris</span>
        </div>

        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b-2 border-black bg-[#F8F7F2] font-black uppercase">
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Tipe</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Deskripsi</th>
                            <th class="p-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/10">
                        @foreach($transactions as $t)
                            <tr class="hover:bg-[#FFFDF0]">
                                <td class="p-3 font-bold text-gray-600 whitespace-nowrap">{{ $t->transaction_date->isoFormat('D MMM Y') }}</td>
                                <td class="p-3 whitespace-nowrap">
                                    <span class="neo-badge text-[10px] {{ $t->type === 'income' ? 'bg-[#DCFCE7] text-green-900' : 'bg-[#FFE4E6] text-red-900' }}">
                                        {{ $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                    </span>
                                </td>
                                <td class="p-3 font-bold whitespace-nowrap">{{ $t->category?->name ?? 'Lainnya' }}</td>
                                <td class="p-3 font-semibold text-gray-800">{{ $t->description }}</td>
                                <td class="p-3 text-right font-black whitespace-nowrap {{ $t->type === 'income' ? 'text-green-700' : 'text-red-600' }}">
                                    {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-xs font-semibold text-gray-500">
                Tidak ada data transaksi yang dapat ditampilkan pada periode ini.
            </div>
        @endif
    </div>

</div>
@endsection
