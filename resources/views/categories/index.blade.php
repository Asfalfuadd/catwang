@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{
    createModalOpen: false,
    editModalOpen: false,
    editCategory: { id: null, name: '', type: 'expense', color: '#FFEB3B' },
    activeTab: 'expense'
}">

    <!-- Page Header -->
    <div class="neo-box p-5 sm:p-6 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="neo-badge bg-[#FFEB3B] mb-1">Manajemen Akun</span>
            <h1 class="text-2xl sm:text-3xl font-black">Kategori Transaksi</h1>
            <p class="text-xs sm:text-sm font-semibold text-gray-600 mt-1">
                Kelompokkan pemasukan dan pengeluaran Anda agar laporan lebih terstruktur.
            </p>
        </div>

        <button type="button" @click="createModalOpen = true" class="neo-btn bg-[#FFEB3B] hover:bg-[#fff066] px-5 py-2.5 text-sm">
            <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span>+ Tambah Kategori Baru</span>
        </button>
    </div>

    <!-- Category Tabs (No emojis) -->
    <div class="flex items-center gap-3">
        <button type="button" 
                @click="activeTab = 'expense'"
                :class="activeTab === 'expense' ? 'bg-[#FF5252] text-white shadow-[4px_4px_0_#000]' : 'bg-white text-black hover:bg-gray-100 shadow-[2px_2px_0_#000]'"
                class="neo-btn px-5 py-2.5 text-sm">
            <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
            <span>Pengeluaran ({{ $expenseCategories->count() }})</span>
        </button>

        <button type="button" 
                @click="activeTab = 'income'"
                :class="activeTab === 'income' ? 'bg-[#4ADE80] text-black shadow-[4px_4px_0_#000]' : 'bg-white text-black hover:bg-gray-100 shadow-[2px_2px_0_#000]'"
                class="neo-btn px-5 py-2.5 text-sm">
            <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            <span>Pemasukan ({{ $incomeCategories->count() }})</span>
        </button>
    </div>

    <!-- Expense Categories Grid (No emojis) -->
    <div x-show="activeTab === 'expense'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($expenseCategories as $cat)
            <div class="neo-box p-4 bg-white hover:-translate-y-0.5 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg border-2 border-black flex items-center justify-center font-bold text-sm shadow-[1px_1px_0_#000]"
                                  style="background-color: {{ $cat->color }};">
                                <svg class="w-4 h-4 text-black stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </span>
                            <h3 class="font-black text-base text-gray-900">{{ $cat->name }}</h3>
                        </div>
                        <span class="neo-badge bg-[#FFE4E6] text-red-900 text-[10px]">Pengeluaran</span>
                    </div>

                    <div class="bg-[#F8F7F2] p-2.5 border border-black rounded-lg text-xs font-semibold text-gray-600 mb-3 space-y-1">
                        <div class="flex justify-between">
                            <span>Jumlah Transaksi:</span>
                            <span class="font-bold text-black">{{ $cat->transactions_count }}x</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Pengeluaran:</span>
                            <span class="font-black text-red-600">Rp {{ number_format($cat->transactions_sum_amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-black/10">
                    <button type="button" 
                            @click="editCategory = { id: {{ $cat->id }}, name: '{{ addslashes($cat->name) }}', type: '{{ $cat->type }}', color: '{{ $cat->color }}' }; editModalOpen = true;"
                            class="neo-btn-sm bg-white hover:bg-[#FFEB3B] text-xs">
                        Edit
                    </button>

                    <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="neo-btn-sm bg-white hover:bg-[#FF5252] hover:text-white text-xs">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Income Categories Grid (No emojis) -->
    <div x-show="activeTab === 'income'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" style="display: none;">
        @foreach($incomeCategories as $cat)
            <div class="neo-box p-4 bg-white hover:-translate-y-0.5 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg border-2 border-black flex items-center justify-center font-bold text-sm shadow-[1px_1px_0_#000]"
                                  style="background-color: {{ $cat->color }};">
                                <svg class="w-4 h-4 text-black stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <h3 class="font-black text-base text-gray-900">{{ $cat->name }}</h3>
                        </div>
                        <span class="neo-badge bg-[#DCFCE7] text-green-900 text-[10px]">Pemasukan</span>
                    </div>

                    <div class="bg-[#F8F7F2] p-2.5 border border-black rounded-lg text-xs font-semibold text-gray-600 mb-3 space-y-1">
                        <div class="flex justify-between">
                            <span>Jumlah Transaksi:</span>
                            <span class="font-bold text-black">{{ $cat->transactions_count }}x</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Pemasukan:</span>
                            <span class="font-black text-green-700">Rp {{ number_format($cat->transactions_sum_amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-black/10">
                    <button type="button" 
                            @click="editCategory = { id: {{ $cat->id }}, name: '{{ addslashes($cat->name) }}', type: '{{ $cat->type }}', color: '{{ $cat->color }}' }; editModalOpen = true;"
                            class="neo-btn-sm bg-white hover:bg-[#FFEB3B] text-xs">
                        Edit
                    </button>

                    <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="neo-btn-sm bg-white hover:bg-[#FF5252] hover:text-white text-xs">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Create Category Modal -->
    <div x-show="createModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="createModalOpen = false" class="fixed inset-0 bg-black/60"></div>
        <div class="neo-box p-6 bg-white w-full max-w-md relative z-10 shadow-[8px_8px_0_#000]">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black mb-4">
                <h3 class="font-black text-lg">Tambah Kategori Baru</h3>
                <button type="button" @click="createModalOpen = false" class="font-black text-lg hover:opacity-75">&times;</button>
            </div>

            <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase mb-1">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Misal: Langganan Streaming" class="neo-input font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase mb-1">Jenis Kategori</label>
                    <select name="type" class="neo-input font-bold">
                        <option value="expense">Pengeluaran (Expense)</option>
                        <option value="income">Pemasukan (Income)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase mb-1">Pilih Warna Badge (Neubrutal)</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="color" value="#FFEB3B" class="w-12 h-10 border-2 border-black rounded-lg cursor-pointer">
                        <span class="text-xs font-semibold text-gray-600">Klik kotak untuk memilih palet warna</span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="createModalOpen = false" class="neo-btn-sm bg-white px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="neo-btn bg-[#FFEB3B] px-5 py-2 text-sm">
                        Simpan Kategori ➔
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="editModalOpen = false" class="fixed inset-0 bg-black/60"></div>
        <div class="neo-box p-6 bg-white w-full max-w-md relative z-10 shadow-[8px_8px_0_#000]">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black mb-4">
                <h3 class="font-black text-lg">Edit Kategori</h3>
                <button type="button" @click="editModalOpen = false" class="font-black text-lg hover:opacity-75">&times;</button>
            </div>

            <form :action="'{{ url('categories') }}/' + editCategory.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-black uppercase mb-1">Nama Kategori</label>
                    <input type="text" name="name" x-model="editCategory.name" required class="neo-input font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase mb-1">Jenis Kategori</label>
                    <select name="type" x-model="editCategory.type" class="neo-input font-bold">
                        <option value="expense">Pengeluaran (Expense)</option>
                        <option value="income">Pemasukan (Income)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase mb-1">Warna Badge</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="color" x-model="editCategory.color" class="w-12 h-10 border-2 border-black rounded-lg cursor-pointer">
                        <span class="text-xs font-semibold text-gray-600">Pilih warna untuk identitas visual</span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="editModalOpen = false" class="neo-btn-sm bg-white px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="neo-btn bg-[#2196F3] text-white px-5 py-2 text-sm">
                        Perbarui Kategori ➔
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
