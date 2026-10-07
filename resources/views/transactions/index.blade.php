@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ deleteModalOpen: false, deleteActionUrl: '', deleteItemName: '' }">

    <!-- Page Title & Actions -->
    <div class="neo-box p-5 sm:p-6 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="neo-badge bg-[#FFEB3B] mb-1">Riwayat Keuangan</span>
            <h1 class="text-2xl sm:text-3xl font-black">Daftar Transaksi</h1>
            <p class="text-xs sm:text-sm font-semibold text-gray-600 mt-1">
                Kelola seluruh catatan pemasukan dan pengeluaran harian Anda.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.create') }}" class="neo-btn bg-[#FFEB3B] hover:bg-[#fff066] px-4 py-2.5 text-sm">
                <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Tambah Transaksi</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar (Neubrutal Box) -->
    <div class="neo-box p-5 bg-white">
        <form method="GET" action="{{ route('transactions.index') }}" class="space-y-4">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <h2 class="font-black text-sm uppercase tracking-wider">Filter & Pencarian</h2>
                </div>
                @if(request()->anyFilled(['type', 'category_id', 'start_date', 'end_date', 'month', 'year', 'search']))
                    <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-red-600 hover:underline flex items-center gap-1">
                        <span>&times;</span> Reset Filter
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Search Keyword -->
                <div>
                    <label class="block text-xs font-black uppercase mb-1">Deskripsi</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari transaksi..." class="neo-input text-xs py-2">
                </div>

                <!-- Transaction Type -->
                <div>
                    <label class="block text-xs font-black uppercase mb-1">Jenis Transaksi</label>
                    <select name="type" class="neo-input text-xs py-2">
                        <option value="">Semua Jenis</option>
                        <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan (+)</option>
                        <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran (-)</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-black uppercase mb-1">Kategori</label>
                    <select name="category_id" class="neo-input text-xs py-2">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->type === 'income' ? 'Masuk' : 'Keluar' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range: Start -->
                <div>
                    <label class="block text-xs font-black uppercase mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="neo-input text-xs py-2">
                </div>

                <!-- Date Range: End -->
                <div>
                    <label class="block text-xs font-black uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="neo-input text-xs py-2">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="submit" class="neo-btn bg-[#2196F3] text-white hover:bg-[#1E88E5] text-xs px-5 py-2">
                    Terapkan Filter ➔
                </button>
            </div>
        </form>
    </div>

    <!-- Filtered Totals Summary Banner (No emojis) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="neo-box-sm p-3.5 bg-[#DCFCE7] flex items-center justify-between">
            <div>
                <p class="text-[11px] font-black uppercase text-green-900">Total Pemasukan Filter</p>
                <p class="text-lg font-black text-green-950 mt-0.5">+ Rp {{ number_format($filteredIncome, 0, ',', '.') }}</p>
            </div>
            <svg class="w-6 h-6 text-green-800 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
        </div>
        <div class="neo-box-sm p-3.5 bg-[#FFE4E6] flex items-center justify-between">
            <div>
                <p class="text-[11px] font-black uppercase text-red-900">Total Pengeluaran Filter</p>
                <p class="text-lg font-black text-red-950 mt-0.5">- Rp {{ number_format($filteredExpense, 0, ',', '.') }}</p>
            </div>
            <svg class="w-6 h-6 text-red-800 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
        </div>
        <div class="neo-box-sm p-3.5 bg-[#FFF9C4] flex items-center justify-between">
            <div>
                <p class="text-[11px] font-black uppercase text-gray-900">Selisih Bersih Filter</p>
                <p class="text-lg font-black {{ $filteredNet >= 0 ? 'text-gray-900' : 'text-red-700' }} mt-0.5">
                    Rp {{ number_format($filteredNet, 0, ',', '.') }}
                </p>
            </div>
            <svg class="w-6 h-6 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
        </div>
    </div>

    <!-- Transactions Table / List -->
    <div class="neo-box bg-white overflow-hidden">
        <div class="p-4 border-b-2 border-black flex items-center justify-between">
            <h2 class="font-black text-base">Hasil Transaksi ({{ $transactions->total() }})</h2>
        </div>

        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-black bg-[#F8F7F2] text-xs font-black uppercase tracking-wider">
                            <th class="p-3.5">Tanggal</th>
                            <th class="p-3.5">Kategori</th>
                            <th class="p-3.5">Deskripsi</th>
                            <th class="p-3.5 text-right">Nominal</th>
                            <th class="p-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-black/10 text-sm">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-[#FFFDF0] transition">
                                <!-- Tanggal -->
                                <td class="p-3.5 whitespace-nowrap font-bold text-gray-600 text-xs">
                                    {{ $tx->transaction_date->isoFormat('D MMM Y') }}
                                </td>

                                <!-- Kategori -->
                                <td class="p-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border border-black font-extrabold text-xs shadow-[1px_1px_0_#000]"
                                          style="background-color: {{ $tx->category?->color ?? '#E5E7EB' }}">
                                        {{ $tx->category?->name ?? 'Tanpa Kategori' }}
                                    </span>
                                </td>

                                <!-- Deskripsi -->
                                <td class="p-3.5 font-bold text-gray-900">
                                    {{ $tx->description }}
                                </td>

                                <!-- Nominal -->
                                <td class="p-3.5 whitespace-nowrap text-right font-black {{ $tx->type === 'income' ? 'text-green-700' : 'text-red-600' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </td>

                                <!-- Aksi -->
                                <td class="p-3.5 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('transactions.edit', $tx) }}" 
                                           class="neo-btn-sm bg-white hover:bg-[#FFEB3B] p-1.5" title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </a>

                                        <button type="button" 
                                                @click="deleteModalOpen = true; deleteActionUrl = '{{ route('transactions.destroy', $tx) }}'; deleteItemName = '{{ addslashes($tx->description) }}'"
                                                class="neo-btn-sm bg-white hover:bg-[#FF5252] hover:text-white p-1.5" title="Hapus">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t-2 border-black bg-[#F8F7F2]">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-10 h-10 mx-auto text-gray-400 stroke-[2] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <h3 class="font-black text-lg mt-1">Tidak Ada Transaksi Ditemukan</h3>
                <p class="text-xs text-gray-500 mt-1 mb-4">Coba sesuaikan kata kunci atau kriteria filter Anda.</p>
                <a href="{{ route('transactions.create') }}" class="neo-btn bg-[#FFEB3B] text-xs px-4 py-2">
                    + Tambah Transaksi Baru
                </a>
            </div>
        @endif
    </div>

    <!-- Neubrutal Delete Confirmation Modal (No emojis) -->
    <div x-show="deleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <!-- Backdrop -->
        <div @click="deleteModalOpen = false" class="fixed inset-0 bg-black/60"></div>

        <!-- Dialog -->
        <div class="neo-box p-6 bg-white w-full max-w-md relative z-10 shadow-[8px_8px_0_#000]">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-[#FF5252] text-white border-2 border-black rounded-xl flex items-center justify-center font-black text-lg">
                    <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="font-black text-lg">Konfirmasi Hapus</h3>
                    <p class="text-xs font-semibold text-gray-600">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <p class="text-sm font-semibold text-gray-800 mb-6 bg-[#FEE2E2] p-3 border-2 border-black rounded-lg">
                Apakah Anda yakin ingin menghapus transaksi: <br>
                <span class="font-black text-black" x-text="deleteItemName"></span>?
            </p>

            <form :action="deleteActionUrl" method="POST" class="flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="deleteModalOpen = false" class="neo-btn-sm bg-white hover:bg-gray-100 px-4 py-2">
                    Batal
                </button>
                <button type="submit" class="neo-btn-sm bg-[#FF5252] text-white hover:bg-red-700 px-4 py-2">
                    Ya, Hapus Transaksi!
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
