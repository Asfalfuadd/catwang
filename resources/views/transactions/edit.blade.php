@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6" x-data="{
    type: '{{ old('type', $transaction->type) }}',
    amount: '{{ old('amount', (int)$transaction->amount) }}',
    selectedCategoryId: '{{ old('category_id', $transaction->category_id) }}',
    categories: {{ Js::from($categories) }},
    get filteredCategories() {
        return this.categories.filter(c => c.type === this.type);
    }
}">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <span class="neo-badge bg-[#FFEB3B] mb-1">Perbarui Catatan</span>
            <h1 class="text-2xl sm:text-3xl font-black">Edit Transaksi #{{ $transaction->id }}</h1>
        </div>
        <a href="{{ route('transactions.index') }}" class="neo-btn-sm bg-white hover:bg-gray-100">
            ← Kembali
        </a>
    </div>

    <!-- Form Box -->
    <div class="neo-box p-6 sm:p-8 bg-white shadow-[6px_6px_0_#000]">
        
        @if($errors->any())
            <div class="mb-6 p-4 bg-[#FEE2E2] border-2 border-black rounded-xl text-xs font-bold text-red-900">
                <p class="font-black text-sm mb-1">Periksa kembali formulir Anda:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- 1. Transaction Type Toggle (No emojis) -->
            <div>
                <label class="block text-xs font-black uppercase tracking-wider mb-2">Jenis Transaksi</label>
                <input type="hidden" name="type" :value="type">
                
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" 
                            @click="type = 'expense'"
                            :class="type === 'expense' ? 'bg-[#FF5252] text-white shadow-[3px_3px_0_#000]' : 'bg-white text-gray-800 hover:bg-gray-100'"
                            class="p-3 border-2 border-black rounded-xl font-black text-sm sm:text-base flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                        <span>Pengeluaran (Expense)</span>
                    </button>

                    <button type="button" 
                            @click="type = 'income'"
                            :class="type === 'income' ? 'bg-[#4ADE80] text-black shadow-[3px_3px_0_#000]' : 'bg-white text-gray-800 hover:bg-gray-100'"
                            class="p-3 border-2 border-black rounded-xl font-black text-sm sm:text-base flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span>Pemasukan (Income)</span>
                    </button>
                </div>
            </div>

            <!-- 2. Category Selection -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="category_id" class="block text-xs font-black uppercase tracking-wider">Kategori</label>
                    <a href="{{ route('categories.index') }}" class="text-xs font-bold text-blue-600 hover:underline">+ Kelola Kategori</a>
                </div>

                <select id="category_id" name="category_id" required class="neo-input font-bold" x-model="selectedCategoryId">
                    <template x-for="cat in filteredCategories" :key="cat.id">
                        <option :value="cat.id" :selected="cat.id == selectedCategoryId" x-text="cat.name"></option>
                    </template>
                </select>
            </div>

            <!-- 3. Amount (Nominal) -->
            <div>
                <label for="amount" class="block text-xs font-black uppercase tracking-wider mb-1.5">Nominal (Rupiah)</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-black text-gray-500">Rp</span>
                    <input id="amount" type="number" step="any" min="1" name="amount" x-model="amount"
                           required placeholder="Contoh: 50000"
                           class="neo-input pl-12 font-black text-lg">
                </div>
                <template x-if="amount && amount > 0">
                    <p class="text-xs font-extrabold text-gray-600 mt-1.5 bg-[#FFF9C4] p-1.5 rounded border border-black inline-block">
                        Terbaca: Rp <span x-text="new Intl.NumberFormat('id-ID').format(amount)"></span>
                    </p>
                </template>
            </div>

            <!-- 4. Transaction Date -->
            <div>
                <label for="transaction_date" class="block text-xs font-black uppercase tracking-wider mb-1.5">Tanggal Transaksi</label>
                <input id="transaction_date" type="date" name="transaction_date" 
                       value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" required
                       class="neo-input font-bold">
            </div>

            <!-- 5. Description -->
            <div>
                <label for="description" class="block text-xs font-black uppercase tracking-wider mb-1.5">Deskripsi / Catatan</label>
                <textarea id="description" name="description" rows="3" required
                          class="neo-input font-medium">{{ old('description', $transaction->description) }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-3 flex items-center justify-end gap-3">
                <a href="{{ route('transactions.index') }}" class="neo-btn bg-white hover:bg-gray-100 text-sm px-5 py-2.5">
                    Batal
                </a>
                <button type="submit" class="neo-btn bg-[#2196F3] text-white hover:bg-[#1E88E5] text-sm px-6 py-2.5">
                    Simpan Perubahan ➔
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
