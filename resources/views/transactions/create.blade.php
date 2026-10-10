@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6" x-data="{
    type: '{{ old('type', $defaultType) }}',
    amount: '{{ old('amount', '') }}',
    categories: {{ Js::from($categories) }},
    get filteredCategories() {
        return this.categories.filter(c => c.type === this.type);
    }
}">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <span class="neo-badge bg-[#FFEB3B] text-black mb-1">Catat Keuangan</span>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">Tambah Transaksi Baru</h1>
        </div>
        <a href="{{ route('transactions.index') }}" class="neo-btn-sm bg-white dark:bg-zinc-800 text-black dark:text-white hover:bg-gray-100 dark:hover:bg-zinc-700">
            ← Kembali
        </a>
    </div>

    <!-- Form Box -->
    <div class="neo-box p-6 sm:p-8 bg-white dark:bg-[#18181B] dark:border-zinc-700 shadow-[6px_6px_0_#000]">
        
        @if($errors->any())
            <div class="mb-6 p-4 bg-[#FEE2E2] dark:bg-rose-950/60 border-2 border-black dark:border-rose-800 rounded-none text-xs font-bold text-red-900 dark:text-rose-200">
                <p class="font-black text-sm mb-1">Periksa kembali formulir Anda:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('transactions.store') }}" class="space-y-5">
            @csrf

            <!-- 1. Transaction Type Toggle (No emojis) -->
            <div>
                <label class="block text-xs font-black uppercase tracking-wider mb-2 text-gray-900 dark:text-white">Jenis Transaksi</label>
                <input type="hidden" name="type" :value="type">
                
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" 
                            @click="type = 'expense'"
                            :class="type === 'expense' ? 'bg-[#FF5252] text-white shadow-[3px_3px_0_#000]' : 'bg-white dark:bg-zinc-800 text-gray-800 dark:text-zinc-200 hover:bg-gray-100 dark:hover:bg-zinc-700 dark:border-zinc-700'"
                            class="p-3 border-2 border-black rounded-none font-black text-sm sm:text-base flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                        <span>Pengeluaran (Expense)</span>
                    </button>

                    <button type="button" 
                            @click="type = 'income'"
                            :class="type === 'income' ? 'bg-[#4ADE80] text-black shadow-[3px_3px_0_#000]' : 'bg-white dark:bg-zinc-800 text-gray-800 dark:text-zinc-200 hover:bg-gray-100 dark:hover:bg-zinc-700 dark:border-zinc-700'"
                            class="p-3 border-2 border-black rounded-none font-black text-sm sm:text-base flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span>Pemasukan (Income)</span>
                    </button>
                </div>
            </div>

            <!-- 2. Category Selection -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="category_id" class="block text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">Kategori</label>
                    <a href="{{ route('categories.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">+ Kelola Kategori</a>
                </div>

                <select id="category_id" name="category_id" required class="neo-input font-bold">
                    <option value="" disabled selected>Pilih Kategori Transaksi</option>
                    <template x-for="cat in filteredCategories" :key="cat.id">
                        <option :value="cat.id" :selected="cat.id == '{{ old('category_id') }}'" x-text="cat.name"></option>
                    </template>
                </select>
            </div>

            <!-- 3. Amount (Nominal) -->
            <div>
                <label for="amount" class="block text-xs font-black uppercase tracking-wider mb-1.5 text-gray-900 dark:text-white">Nominal (Rupiah)</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-black text-gray-500 dark:text-zinc-400">Rp</span>
                    <input id="amount" type="number" step="any" min="1" name="amount" x-model="amount"
                           value="{{ old('amount') }}" required placeholder="Contoh: 50000"
                           class="neo-input pl-12 font-black text-lg">
                </div>
                <template x-if="amount && amount > 0">
                    <p class="text-xs font-extrabold text-gray-800 dark:text-amber-200 mt-1.5 bg-[#FFF9C4] dark:bg-amber-950/70 p-1.5 rounded-none border border-black dark:border-amber-800 inline-block">
                        Terbaca: Rp <span x-text="new Intl.NumberFormat('id-ID').format(amount)"></span>
                    </p>
                </template>
            </div>

            <!-- 4. Transaction Date -->
            <div>
                <label for="transaction_date" class="block text-xs font-black uppercase tracking-wider mb-1.5 text-gray-900 dark:text-white">Tanggal Transaksi</label>
                <input id="transaction_date" type="date" name="transaction_date" 
                       value="{{ old('transaction_date', now()->toDateString()) }}" required
                       class="neo-input font-bold">
            </div>

            <!-- 5. Description -->
            <div>
                <label for="description" class="block text-xs font-black uppercase tracking-wider mb-1.5 text-gray-900 dark:text-white">Deskripsi / Catatan</label>
                <textarea id="description" name="description" rows="3" required
                          placeholder="Misal: Makan Siang Nasi Padang bersama rekan kantor"
                          class="neo-input font-medium">{{ old('description') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-3 flex items-center justify-end gap-3">
                <a href="{{ route('transactions.index') }}" class="neo-btn bg-white dark:bg-zinc-800 text-black dark:text-white hover:bg-gray-100 dark:hover:bg-zinc-700 text-sm px-5 py-2.5">
                    Batal
                </a>
                <button type="submit" class="neo-btn bg-[#FFEB3B] text-black hover:bg-[#fff066] text-sm px-6 py-2.5">
                    Simpan Transaksi ➔
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
