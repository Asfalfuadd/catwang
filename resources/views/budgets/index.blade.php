@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ createModalOpen: false, editModalOpen: false, editBudget: { id: null, amount: 0, categoryName: '' } }">

    <!-- Page Header -->
    <div class="neo-box p-5 sm:p-6 bg-white dark:bg-[#18181B] dark:border-zinc-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="neo-badge bg-[#FFEB3B] text-black mb-1">Rencana Keuangan</span>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">Anggaran Bulanan (Budget)</h1>
            <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 mt-1">
                Tetapkan batas pengeluaran maksimum per kategori agar pengeluaran tetap terkontrol.
            </p>
        </div>

        <button type="button" @click="createModalOpen = true" class="neo-btn bg-[#FFEB3B] text-black hover:bg-[#fff066] px-4 sm:px-5 py-2.5 text-xs sm:text-sm w-full sm:w-auto flex items-center justify-center gap-2">
            <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span>+ Tetapkan Anggaran</span>
        </button>
    </div>

    <!-- Overview Banner (No emojis) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="neo-box-sm p-4 bg-white dark:bg-[#18181B] dark:border-zinc-700 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-black uppercase text-gray-500 dark:text-gray-400">Total Plafon Anggaran</p>
                <p class="text-xl font-black text-gray-900 dark:text-white mt-1">Rp {{ number_format($totalBudgeted, 0, ',', '.') }}</p>
            </div>
            <svg class="w-7 h-7 text-gray-700 dark:text-gray-300 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="neo-box-sm p-4 bg-[#FFE4E6] dark:bg-red-950/60 dark:border-zinc-700 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-black uppercase text-red-900 dark:text-red-300">Total Terpakai Bulan Ini</p>
                <p class="text-xl font-black text-red-950 dark:text-red-200 mt-1">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
            </div>
            <svg class="w-7 h-7 text-red-800 dark:text-red-300 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
        </div>
        <div class="neo-box-sm p-4 bg-[#DCFCE7] dark:bg-emerald-950/60 dark:border-zinc-700 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-black uppercase text-green-900 dark:text-emerald-300">Sisa Kuota Anggaran</p>
                <p class="text-xl font-black text-green-950 dark:text-emerald-200 mt-1">
                    Rp {{ number_format(max(0, $totalBudgeted - $totalSpent), 0, ',', '.') }}
                </p>
            </div>
            <svg class="w-7 h-7 text-green-800 dark:text-emerald-300 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
    </div>

    <!-- Budgets Grid (No emojis) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($budgets as $b)
            <div class="neo-box p-5 bg-white dark:bg-[#18181B] dark:border-zinc-700 flex flex-col justify-between {{ $b->is_over ? 'border-red-500 dark:border-red-500 shadow-[4px_4px_0_#EF4444]' : '' }}">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-none border-2 border-black dark:border-zinc-700 flex items-center justify-center font-bold text-sm shadow-[1px_1px_0_#000]"
                                  style="background-color: {{ $b->category?->color ?? '#FFEB3B' }};">
                                <svg class="w-4 h-4 text-black stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </span>
                            <div>
                                <h3 class="font-black text-base text-gray-900 dark:text-white">{{ $b->category?->name }}</h3>
                                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Periode Bulanan</span>
                            </div>
                        </div>

                        <span class="neo-badge text-[10px] {{ $b->is_over ? 'bg-[#FF5252] text-white' : 'bg-[#E0F2FE] dark:bg-sky-950 dark:text-sky-300 text-blue-900' }}">
                            {{ $b->is_over ? 'Over Budget!' : $b->percentage . '% Terpakai' }}
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="my-4">
                        <div class="w-full bg-gray-200 dark:bg-zinc-800 border-2 border-black dark:border-zinc-700 rounded-none h-4 overflow-hidden p-0.5">
                            <div class="h-full rounded-none border border-black {{ $b->is_over ? 'bg-[#FF5252]' : ($b->percentage > 80 ? 'bg-[#FF9800]' : 'bg-[#4ADE80]') }} transition-all duration-300"
                                  style="width: {{ $b->percentage }}%"></div>
                        </div>
                    </div>

                    <div class="bg-[#F8F7F2] dark:bg-[#27272A] p-3 border border-black dark:border-zinc-700 rounded-none text-xs font-semibold space-y-1.5 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Realisasi Pengeluaran:</span>
                            <span class="font-black {{ $b->is_over ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                Rp {{ number_format($b->spent, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Batas Maksimum:</span>
                            <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($b->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-1 border-t border-black/10 dark:border-zinc-700">
                            <span class="text-gray-600 dark:text-gray-400">Sisa Anggaran:</span>
                            <span class="font-black {{ $b->remaining < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-700 dark:text-emerald-400' }}">
                                Rp {{ number_format($b->remaining, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-black/10 dark:border-zinc-700">
                    <button type="button" 
                            @click="editBudget = { id: {{ $b->id }}, amount: {{ (int)$b->amount }}, categoryName: '{{ addslashes($b->category?->name) }}' }; editModalOpen = true;"
                            class="neo-btn-sm bg-white dark:bg-zinc-800 dark:text-white hover:bg-[#FFEB3B] dark:hover:text-black text-xs">
                        Ubah Batas
                    </button>
                    <form method="POST" action="{{ route('budgets.destroy', $b) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggaran ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="neo-btn-sm bg-white dark:bg-zinc-800 dark:text-white hover:bg-[#FF5252] hover:text-white text-xs">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-3 p-12 text-center bg-white dark:bg-[#18181B] dark:border-zinc-700 neo-box">
                <svg class="w-10 h-10 mx-auto text-gray-400 stroke-[2] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="font-black text-lg text-gray-900 dark:text-white mt-1">Belum Ada Anggaran Dibuat</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">Tentukan batas pengeluaran untuk kategori favorit Anda.</p>
                <button type="button" @click="createModalOpen = true" class="neo-btn bg-[#FFEB3B] text-black text-xs px-4 py-2">
                    + Buat Anggaran Pertama
                </button>
            </div>
        @endforelse
    </div>

    <!-- Create Budget Modal -->
    <div x-show="createModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="createModalOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="neo-box p-6 bg-white dark:bg-[#18181B] dark:border-zinc-700 w-full max-w-md relative z-10 shadow-[8px_8px_0_#000] text-gray-900 dark:text-white">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black dark:border-zinc-700 mb-4">
                <h3 class="font-black text-lg">Tetapkan Anggaran Bulanan</h3>
                <button type="button" @click="createModalOpen = false" class="font-black text-lg hover:opacity-75">&times;</button>
            </div>

            <form method="POST" action="{{ route('budgets.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="period" value="monthly">

                <div>
                    <label class="block text-xs font-black uppercase mb-1 dark:text-gray-300">Pilih Kategori Pengeluaran</label>
                    <select name="category_id" required class="neo-input font-bold">
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach($expenseCategories as $ec)
                            <option value="{{ $ec->id }}">{{ $ec->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase mb-1 dark:text-gray-300">Batas Maksimal Anggaran (Rp)</label>
                    <input type="number" step="any" min="1000" name="amount" required placeholder="Contoh: 1500000" class="neo-input font-bold">
                </div>

                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="createModalOpen = false" class="neo-btn-sm bg-white dark:bg-zinc-800 dark:text-white px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="neo-btn bg-[#FFEB3B] text-black px-5 py-2 text-sm">
                        Simpan Anggaran ➔
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Budget Modal -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="editModalOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="neo-box p-6 bg-white dark:bg-[#18181B] dark:border-zinc-700 w-full max-w-md relative z-10 shadow-[8px_8px_0_#000] text-gray-900 dark:text-white">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black dark:border-zinc-700 mb-4">
                <h3 class="font-black text-lg">Ubah Batas Anggaran: <span x-text="editBudget.categoryName"></span></h3>
                <button type="button" @click="editModalOpen = false" class="font-black text-lg hover:opacity-75">&times;</button>
            </div>

            <form :action="'{{ url('budgets') }}/' + editBudget.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-black uppercase mb-1 dark:text-gray-300">Batas Anggaran Baru (Rp)</label>
                    <input type="number" step="any" min="1000" name="amount" x-model="editBudget.amount" required class="neo-input font-bold">
                </div>

                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="editModalOpen = false" class="neo-btn-sm bg-white dark:bg-zinc-800 dark:text-white px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="neo-btn bg-[#2196F3] text-white px-5 py-2 text-sm">
                        Perbarui Anggaran ➔
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
