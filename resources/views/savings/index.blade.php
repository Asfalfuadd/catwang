@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{
    createModalOpen: false,
    addFundsModalOpen: false,
    activeGoal: { id: null, name: '' }
}">

    <!-- Page Header -->
    <div class="neo-box p-5 sm:p-6 bg-white dark:bg-[#18181B] dark:border-zinc-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="neo-badge bg-[#FFEB3B] text-black mb-1">Masa Depan</span>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">Target Tabungan (Saving Goals)</h1>
            <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 mt-1">
                Wujudkan impian dan dana darurat Anda dengan menabung secara konsisten.
            </p>
        </div>

        <button type="button" @click="createModalOpen = true" class="neo-btn bg-[#FFEB3B] text-black hover:bg-[#fff066] px-4 sm:px-5 py-2.5 text-xs sm:text-sm w-full sm:w-auto flex items-center justify-center gap-2">
            <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span>+ Buat Target Tabungan</span>
        </button>
    </div>

    <!-- Summary Banner (No emojis) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="neo-box-sm p-4 bg-white dark:bg-[#18181B] dark:border-zinc-700 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-black uppercase text-gray-500 dark:text-gray-400">Total Akumulasi Target</p>
                <p class="text-xl font-black text-gray-900 dark:text-white mt-1">Rp {{ number_format($totalTarget, 0, ',', '.') }}</p>
            </div>
            <svg class="w-7 h-7 text-gray-700 dark:text-gray-300 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
        </div>
        <div class="neo-box-sm p-4 bg-[#DCFCE7] dark:bg-emerald-950/60 dark:border-zinc-700 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-black uppercase text-green-900 dark:text-emerald-300">Total Dana Terkumpul</p>
                <p class="text-xl font-black text-green-950 dark:text-emerald-200 mt-1">Rp {{ number_format($totalCollected, 0, ',', '.') }}</p>
            </div>
            <svg class="w-7 h-7 text-green-800 dark:text-emerald-300 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
    </div>

    <!-- Goals Grid (No emojis) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($goals as $goal)
            <div class="neo-box p-5 bg-white dark:bg-[#18181B] dark:border-zinc-700 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-9 h-9 rounded-none border-2 border-black dark:border-zinc-700 bg-[#FFEB3B] flex items-center justify-center shadow-[1px_1px_0_#000]">
                                <svg class="w-5 h-5 text-black stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </span>
                            <div>
                                <h3 class="font-black text-base text-gray-900 dark:text-white">{{ $goal->name }}</h3>
                                @if($goal->target_date)
                                    <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Target: {{ $goal->target_date->isoFormat('D MMM Y') }}</span>
                                @endif
                            </div>
                        </div>

                        <span class="neo-badge text-[10px] {{ $goal->percentage >= 100 ? 'bg-[#4ADE80] text-black' : 'bg-[#E0F2FE] dark:bg-sky-950 dark:text-sky-300 text-blue-950' }}">
                            {{ $goal->percentage }}% Terpenuhi
                        </span>
                    </div>

                    @if($goal->description)
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-300 mb-3 bg-[#F8F7F2] dark:bg-[#27272A] p-2 rounded-none border border-black/10 dark:border-zinc-700">
                            {{ $goal->description }}
                        </p>
                    @endif

                    <!-- Progress Bar -->
                    <div class="my-3">
                        <div class="w-full bg-gray-200 dark:bg-zinc-800 border-2 border-black dark:border-zinc-700 rounded-none h-4 overflow-hidden p-0.5">
                            <div class="h-full rounded-none border border-black {{ $goal->percentage >= 100 ? 'bg-[#4ADE80]' : 'bg-[#2196F3]' }} transition-all duration-300"
                                 style="width: {{ $goal->percentage }}%"></div>
                        </div>
                    </div>

                    <div class="bg-[#F8F7F2] dark:bg-[#27272A] p-3 border border-black dark:border-zinc-700 rounded-none text-xs font-semibold space-y-1 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Terkumpul Saat Ini:</span>
                            <span class="font-black text-green-700 dark:text-emerald-400">Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Total Sasaran:</span>
                            <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-1 border-t border-black/10 dark:border-zinc-700">
                            <span class="text-gray-600 dark:text-gray-400">Kekurangan:</span>
                            <span class="font-bold text-gray-800 dark:text-gray-300">Rp {{ number_format($goal->remaining, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2 pt-2 border-t border-black/10 dark:border-zinc-700">
                    <button type="button" 
                            @click="activeGoal = { id: {{ $goal->id }}, name: '{{ addslashes($goal->name) }}' }; addFundsModalOpen = true;"
                            class="neo-btn-sm bg-[#4ADE80] text-black hover:bg-[#22c55e] text-xs">
                        + Tambah Setoran
                    </button>

                    <form method="POST" action="{{ route('savings.destroy', $goal) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus target tabungan ini?')">
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
                <svg class="w-10 h-10 mx-auto text-gray-400 stroke-[2] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <h3 class="font-black text-lg text-gray-900 dark:text-white mt-1">Belum Ada Target Tabungan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">Buat target tabungan seperti Dana Darurat, Liburan, atau Beli Gadget.</p>
                <button type="button" @click="createModalOpen = true" class="neo-btn bg-[#FFEB3B] text-black text-xs px-4 py-2">
                    + Buat Target Pertama
                </button>
            </div>
        @endforelse
    </div>

    <!-- Create Goal Modal -->
    <div x-show="createModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="createModalOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="neo-box p-6 bg-white dark:bg-[#18181B] dark:border-zinc-700 w-full max-w-md relative z-10 shadow-[8px_8px_0_#000] text-gray-900 dark:text-white">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black dark:border-zinc-700 mb-4">
                <h3 class="font-black text-lg">Buat Target Tabungan Baru</h3>
                <button type="button" @click="createModalOpen = false" class="font-black text-lg hover:opacity-75">&times;</button>
            </div>

            <form method="POST" action="{{ route('savings.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-black uppercase mb-1 dark:text-gray-300">Nama Target</label>
                    <input type="text" name="name" required placeholder="Misal: Dana Darurat 6 Bulan" class="neo-input font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase mb-1 dark:text-gray-300">Target Nominal (Rp)</label>
                    <input type="number" step="any" min="1000" name="target_amount" required placeholder="Contoh: 10000000" class="neo-input font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase mb-1 dark:text-gray-300">Tabungan Awal (Opsional)</label>
                    <input type="number" step="any" min="0" name="current_amount" placeholder="0" class="neo-input font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase mb-1 dark:text-gray-300">Target Tanggal Tercapai (Opsional)</label>
                    <input type="date" name="target_date" class="neo-input font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase mb-1 dark:text-gray-300">Catatan / Deskripsi (Opsional)</label>
                    <textarea name="description" rows="2" placeholder="Catatan singkat tentang target ini..." class="neo-input text-xs"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="createModalOpen = false" class="neo-btn-sm bg-white dark:bg-zinc-800 dark:text-white px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="neo-btn bg-[#FFEB3B] text-black px-5 py-2 text-sm">
                        Simpan Target ➔
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Funds Modal -->
    <div x-show="addFundsModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="addFundsModalOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="neo-box p-6 bg-white dark:bg-[#18181B] dark:border-zinc-700 w-full max-w-md relative z-10 shadow-[8px_8px_0_#000] text-gray-900 dark:text-white">
            <div class="flex items-center justify-between pb-3 border-b-2 border-black dark:border-zinc-700 mb-4">
                <h3 class="font-black text-lg">Tambah Setoran: <span x-text="activeGoal.name"></span></h3>
                <button type="button" @click="addFundsModalOpen = false" class="font-black text-lg hover:opacity-75">&times;</button>
            </div>

            <form :action="'{{ url('savings') }}/' + activeGoal.id + '/add'" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-black uppercase mb-1 dark:text-gray-300">Nominal Tambahan (Rp)</label>
                    <input type="number" step="any" min="1000" name="amount" required placeholder="Contoh: 500000" class="neo-input font-black text-lg">
                </div>

                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="addFundsModalOpen = false" class="neo-btn-sm bg-white dark:bg-zinc-800 dark:text-white px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="neo-btn bg-[#4ADE80] text-black px-5 py-2 text-sm font-black">
                        + Setor Sekarang ➔
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
