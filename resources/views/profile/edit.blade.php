@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{
    activeTab: 'profile',
    selectedTheme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
    selectTheme(theme) {
        this.selectedTheme = theme;
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('catwang_theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('catwang_theme', 'light');
        }
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: theme === 'dark' } }));
        // Sync with backend
        fetch('{{ route('profile.theme.update') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ theme: theme })
        }).catch(() => {});
    }
}">

    <!-- Page Title & Header Banner -->
    <div class="neo-box p-6 bg-white dark:bg-[#18181B] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#FFEB3B] text-black border-2 border-black rounded-none flex items-center justify-center font-black shadow-[2px_2px_0_#000]">
                    <svg class="w-6 h-6 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">Profil & Pengaturan Akun</h1>
                    <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 mt-0.5">
                        Kelola identitas, foto profil, preferensi tema tampilan, dan keamanan kata sandi Anda.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="neo-badge bg-[#DCFCE7] dark:bg-emerald-900/60 text-green-900 dark:text-emerald-300 border-black">
                Akun Terverifikasi
            </span>
        </div>
    </div>

    <!-- Layout Grid: Left Sidebar Info & Main Forms -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left Column: Avatar & Account Card (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Avatar Card with Cropper -->
            <div class="neo-box p-6 bg-white dark:bg-[#18181B] text-center relative overflow-hidden" 
                 x-data="{ 
                     photoPreview: null,
                     cropModalOpen: false,
                     cropImageSrc: null,
                     cropperInstance: null,
                     isUploading: false,
                     openFilePicker() { $refs.photoInput.click() },
                     onFileSelected(e) {
                         const file = e.target.files[0];
                         if (!file) return;
                         if (!file.type.startsWith('image/')) {
                             alert('File harus berupa gambar (JPG, PNG, WebP).');
                             return;
                         }
                         const reader = new FileReader();
                         reader.onload = (event) => {
                             this.cropImageSrc = event.target.result;
                             this.cropModalOpen = true;
                             this.$nextTick(() => {
                                 this.initCropper();
                             });
                         };
                         reader.readAsDataURL(file);
                     },
                     initCropper() {
                         if (this.cropperInstance) {
                             this.cropperInstance.destroy();
                         }
                         const img = document.getElementById('avatarCropperTarget');
                         if (!img || !window.Cropper) return;
                         this.cropperInstance = new window.Cropper(img, {
                             aspectRatio: 1,
                             viewMode: 1,
                             dragMode: 'move',
                             autoCropArea: 0.9,
                             restore: false,
                             guides: true,
                             center: true,
                             highlight: false,
                             cropBoxMovable: true,
                             cropBoxResizable: true,
                             toggleDragModeOnDblclick: false,
                             background: true
                         });
                     },
                     zoom(ratio) {
                         if (this.cropperInstance) this.cropperInstance.zoom(ratio);
                     },
                     rotate(deg) {
                         if (this.cropperInstance) this.cropperInstance.rotate(deg);
                     },
                     resetCropper() {
                         if (this.cropperInstance) this.cropperInstance.reset();
                     },
                     cancelCrop() {
                         if (this.cropperInstance) {
                             this.cropperInstance.destroy();
                             this.cropperInstance = null;
                         }
                         this.cropModalOpen = false;
                         this.cropImageSrc = null;
                         this.$refs.photoInput.value = '';
                     },
                     applyCrop() {
                         if (!this.cropperInstance) return;
                         const canvas = this.cropperInstance.getCroppedCanvas({
                             width: 512,
                             height: 512,
                             imageSmoothingEnabled: true,
                             imageSmoothingQuality: 'high'
                         });
                         if (!canvas) {
                             alert('Gagal memproses crop gambar.');
                             return;
                         }
                         const base64 = canvas.toDataURL('image/jpeg', 0.90);
                         this.photoPreview = base64;
                         this.$refs.avatarBase64Input.value = base64;
                         if (this.$refs.photoInput) {
                             this.$refs.photoInput.value = '';
                         }
                         this.isUploading = true;
                         this.cropModalOpen = false;
                         if (this.cropperInstance) {
                             this.cropperInstance.destroy();
                             this.cropperInstance = null;
                         }
                         this.$refs.avatarForm.submit();
                     }
                 }">
                
                <div class="relative inline-block mx-auto mb-4 group">
                    <!-- Photo Display -->
                    <template x-if="photoPreview">
                        <img :src="photoPreview" alt="Preview Foto" class="w-28 h-28 object-cover rounded-none border-3 border-black shadow-[4px_4px_0_#000]">
                    </template>
                    <template x-if="!photoPreview">
                        <div>
                            @if($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-28 h-28 object-cover rounded-none border-3 border-black shadow-[4px_4px_0_#000]">
                            @else
                                <div class="w-28 h-28 bg-[#2196F3] text-white border-3 border-black rounded-none shadow-[4px_4px_0_#000] flex items-center justify-center font-black text-4xl uppercase">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </template>

                    <!-- Camera overlay button -->
                    <button type="button" @click="openFilePicker()" 
                            class="absolute -bottom-2 -right-2 neo-btn-sm bg-[#FFEB3B] text-black p-2 rounded-none"
                            title="Ganti & Crop Foto Profil">
                        <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <h2 class="text-xl font-black text-gray-900 dark:text-white truncate">{{ $user->name }}</h2>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mt-1 truncate">{{ $user->email }}</p>

                @if($errors->has('avatar') || $errors->has('avatar_base64'))
                    <div class="mt-3 p-2.5 bg-red-100 dark:bg-red-950/70 border-2 border-red-500 rounded-none text-xs font-bold text-red-600 dark:text-red-300">
                        {{ $errors->first('avatar') ?: $errors->first('avatar_base64') }}
                    </div>
                @endif

                <!-- Dedicated Upload Form for Avatar -->
                <form x-ref="avatarForm" method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <input type="file" name="avatar" x-ref="photoInput" accept="image/*" class="hidden" @change="onFileSelected($event)">
                    <input type="hidden" name="avatar_base64" x-ref="avatarBase64Input" value="">

                    <div class="flex flex-col gap-2 mt-3">
                        <button type="button" @click="openFilePicker()" class="neo-btn bg-white dark:bg-[#27272A] hover:bg-gray-100 dark:hover:bg-zinc-700 text-xs py-2 w-full text-black dark:text-white">
                            <svg class="w-4 h-4 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Pilih & Crop Foto
                        </button>
                    </div>
                </form>

                @if($user->avatar)
                    <form method="POST" action="{{ route('profile.avatar.delete') }}" class="mt-2" onsubmit="return confirm('Hapus foto profil dan gunakan inisial nama?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-600 dark:text-red-400 hover:underline">
                            Hapus Foto Profil
                        </button>
                    </form>
                @endif

                <div class="border-t-2 border-black dark:border-zinc-700 mt-5 pt-4 text-left text-xs space-y-2">
                    <div class="flex justify-between items-center text-gray-600 dark:text-gray-400">
                        <span class="font-bold">Bergabung:</span>
                        <span class="font-extrabold text-black dark:text-white">{{ $user->created_at ? $user->created_at->isoFormat('D MMMM Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600 dark:text-gray-400">
                        <span class="font-bold">Total Transaksi:</span>
                        <span class="font-extrabold text-black dark:text-white">{{ $user->transactions()->count() }} catatan</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600 dark:text-gray-400">
                        <span class="font-bold">Tema Pilihan:</span>
                        <span class="font-black uppercase tracking-wider text-black dark:text-yellow-400" x-text="selectedTheme === 'dark' ? 'Mode Gelap' : 'Mode Terang'">
                            {{ $user->theme === 'dark' ? 'Mode Gelap' : 'Mode Terang' }}
                        </span>
                    </div>
                </div>

                <!-- Crop Image Modal -->
                <div x-show="cropModalOpen" 
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs" 
                     style="display: none;"
                     @keydown.escape.window="cancelCrop()">
                    <div class="neo-box p-5 sm:p-6 bg-white dark:bg-[#18181B] w-full max-w-lg relative z-10 shadow-[8px_8px_0_#000] text-left">
                        <div class="flex items-center justify-between pb-3 border-b-2 border-black dark:border-zinc-700 mb-4">
                            <div>
                                <h3 class="font-black text-lg text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-yellow-500 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Crop Foto Profil (1:1)
                                </h3>
                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mt-0.5">
                                    Geser area kotak crop atau gunakan tombol kontrol di bawah.
                                </p>
                            </div>
                            <button type="button" @click="cancelCrop()" class="font-black text-xl hover:opacity-75 text-black dark:text-white">&times;</button>
                        </div>

                        <!-- Cropper Canvas Container -->
                        <div class="w-full h-64 sm:h-72 bg-zinc-950 rounded-none overflow-hidden border-2 border-black dark:border-zinc-700 relative flex items-center justify-center">
                            <template x-if="cropImageSrc">
                                <img id="avatarCropperTarget" :src="cropImageSrc" alt="Source Image" class="max-w-full block">
                            </template>
                        </div>

                        <!-- Cropper Controls Toolbar -->
                        <div class="flex flex-wrap items-center justify-between gap-2 mt-3 pt-3 border-t-2 border-black dark:border-zinc-700">
                            <div class="flex items-center gap-1.5">
                                <button type="button" @click="zoom(0.1)" class="neo-btn-sm bg-white dark:bg-zinc-800 text-black dark:text-white text-xs px-2.5 py-1.5" title="Perbesar">
                                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                </button>
                                <button type="button" @click="zoom(-0.1)" class="neo-btn-sm bg-white dark:bg-zinc-800 text-black dark:text-white text-xs px-2.5 py-1.5" title="Perkecil">
                                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                                </button>
                                <button type="button" @click="rotate(-90)" class="neo-btn-sm bg-white dark:bg-zinc-800 text-black dark:text-white text-xs px-2.5 py-1.5" title="Putar Kiri 90°">
                                    ↶ 90°
                                </button>
                                <button type="button" @click="rotate(90)" class="neo-btn-sm bg-white dark:bg-zinc-800 text-black dark:text-white text-xs px-2.5 py-1.5" title="Putar Kanan 90°">
                                    ↷ 90°
                                </button>
                                <button type="button" @click="resetCropper()" class="neo-btn-sm bg-white dark:bg-zinc-800 text-black dark:text-white text-xs px-2.5 py-1.5" title="Reset">
                                    Reset
                                </button>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 mt-4 pt-3 border-t-2 border-black dark:border-zinc-700">
                            <button type="button" @click="cancelCrop()" class="neo-btn-sm bg-white dark:bg-zinc-800 text-black dark:text-white px-4 py-2 text-xs">
                                Batal
                            </button>
                            <button type="button" @click="applyCrop()" :disabled="isUploading" class="neo-btn bg-[#22C55E] text-white hover:bg-emerald-600 px-5 py-2 text-xs shadow-[2px_2px_0_#000]">
                                <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="isUploading ? 'Menyimpan...' : 'Terapkan & Simpan Foto'"></span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Quick Navigation Card -->
            <div class="neo-box p-4 bg-white dark:bg-[#18181B] space-y-1">
                <button type="button" @click="activeTab = 'profile'" 
                        :class="activeTab === 'profile' ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-none font-bold text-sm border-2 transition-all text-left">
                    <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Identitas Profil</span>
                </button>

                <button type="button" @click="activeTab = 'theme'" 
                        :class="activeTab === 'theme' ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-none font-bold text-sm border-2 transition-all text-left">
                    <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    <span>Preferensi Tema</span>
                </button>

                <button type="button" @click="activeTab = 'security'" 
                        :class="activeTab === 'security' ? 'bg-[#FFEB3B] text-black border-black shadow-[2px_2px_0_#000]' : 'border-transparent text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800'"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-none font-bold text-sm border-2 transition-all text-left">
                    <svg class="w-5 h-5 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span>Keamanan Password</span>
                </button>
            </div>

        </div>

        <!-- Right Column: Settings Forms (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- TAB 1: IDENTITAS PROFIL (Ganti Username & Ganti Email) -->
            <div x-show="activeTab === 'profile'" class="neo-box p-6 bg-white dark:bg-[#18181B] space-y-6">
                <div class="border-b-2 border-black dark:border-zinc-700 pb-4">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white">Identitas & Akun</h2>
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mt-1">
                        Perbarui nama pengguna (username) dan alamat email akun Anda.
                    </p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <!-- Username / Nama Pengguna Field -->
                    <div>
                        <label for="name" class="block font-black text-sm mb-1.5 text-gray-900 dark:text-white">
                            Nama Pengguna (Username) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="name" name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required 
                                   class="neo-input @error('name') border-red-500 @enderror"
                                   placeholder="Masukkan username Anda">
                        </div>
                        @error('name')
                            <p class="text-xs font-bold text-red-600 dark:text-red-400 mt-1.5">{{ $message }}</p>
                        @enderror
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1">
                            Nama ini akan ditampilkan pada salam dashboard dan transaksi Anda.
                        </p>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block font-black text-sm mb-1.5 text-gray-900 dark:text-white">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="email" id="email" name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required 
                                   class="neo-input @error('email') border-red-500 @enderror"
                                   placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <p class="text-xs font-bold text-red-600 dark:text-red-400 mt-1.5">{{ $message }}</p>
                        @enderror
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1">
                            Email digunakan untuk masuk (login) ke dalam sistem CatWang.
                        </p>
                    </div>

                    <div class="pt-3 border-t-2 border-black dark:border-zinc-700 flex justify-end">
                        <button type="submit" class="neo-btn bg-[#FFEB3B] text-black hover:bg-[#fff066] px-6 py-2.5 text-sm">
                            <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: PREFERENSI TEMA (Terang vs Gelap) -->
            <div x-show="activeTab === 'theme'" class="neo-box p-6 bg-white dark:bg-[#18181B] space-y-6" style="display: none;">
                <div class="border-b-2 border-black dark:border-zinc-700 pb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white">Tema Tampilan Aplikasi</h2>
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mt-1">
                                Pilih tema yang paling nyaman untuk mata Anda. Perubahan langsung aktif seketika!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Theme Selection Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <!-- Option 1: Tema Terang -->
                    <div @click="selectTheme('light')" 
                         :class="selectedTheme === 'light' ? 'border-4 border-black dark:border-yellow-400 ring-4 ring-[#FFEB3B] bg-amber-50/50 dark:bg-amber-950/30' : 'border-2 border-black dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700'"
                         class="cursor-pointer neo-box p-5 transition-all relative overflow-hidden group">
                        
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-[#FFEB3B] text-black border-2 border-black rounded-none flex items-center justify-center font-black shadow-[2px_2px_0_#000]">
                                <svg class="w-6 h-6 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span x-show="selectedTheme === 'light'" class="neo-badge bg-[#22C55E] text-white border-black">
                                Aktif
                            </span>
                        </div>

                        <h3 class="font-black text-base text-gray-900 dark:text-white">Tema Terang (Light)</h3>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-300 mt-1 leading-relaxed">
                            Warna cerah ceria dengan kontras tajam neubrutalism khas CatWang. Cocok digunakan di siang hari.
                        </p>

                        <!-- Preview mockup -->
                        <div class="mt-4 p-2 bg-[#F8F7F2] dark:bg-zinc-900 border-2 border-black dark:border-zinc-700 rounded-none space-y-1.5">
                            <div class="h-2.5 bg-[#FFEB3B] border border-black rounded-none w-3/4"></div>
                            <div class="h-2 bg-white dark:bg-zinc-700 border border-black dark:border-zinc-600 rounded-none w-full"></div>
                            <div class="h-2 bg-white dark:bg-zinc-700 border border-black dark:border-zinc-600 rounded-none w-1/2"></div>
                        </div>
                    </div>

                    <!-- Option 2: Tema Gelap -->
                    <div @click="selectTheme('dark')" 
                         :class="selectedTheme === 'dark' ? 'border-4 border-black ring-4 ring-[#FFEB3B] bg-zinc-900 text-white' : 'border-2 border-black bg-[#1f1f23] text-white hover:bg-zinc-800'"
                         class="cursor-pointer neo-box p-5 transition-all relative overflow-hidden group">
                        
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-indigo-600 text-white border-2 border-black rounded-none flex items-center justify-center font-black shadow-[2px_2px_0_#000]">
                                <svg class="w-6 h-6 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.03 9.03 0 008.354-5.646z" />
                                </svg>
                            </div>
                            <span x-show="selectedTheme === 'dark'" class="neo-badge bg-[#22C55E] text-white border-black" style="display: none;">
                                Aktif
                            </span>
                        </div>

                        <h3 class="font-black text-base text-white">Tema Gelap (Dark)</h3>
                        <p class="text-xs font-medium text-zinc-300 mt-1 leading-relaxed">
                            Latar belakang gelap pekat yang elegan, hemat baterai dan sangat bersahabat untuk mata di malam hari.
                        </p>

                        <!-- Preview mockup -->
                        <div class="mt-4 p-2 bg-[#121214] border-2 border-zinc-700 rounded-none space-y-1.5">
                            <div class="h-2.5 bg-yellow-400 border border-black rounded-none w-3/4"></div>
                            <div class="h-2 bg-zinc-800 border border-zinc-700 rounded-none w-full"></div>
                            <div class="h-2 bg-zinc-800 border border-zinc-700 rounded-none w-1/2"></div>
                        </div>
                    </div>

                </div>

                <!-- Live Notification & Quick Toggle Tip -->
                <div class="p-4 bg-[#FFF9C4] dark:bg-yellow-950/40 border-2 border-black rounded-none text-xs font-semibold text-black dark:text-yellow-200 flex items-center gap-3">
                    <svg class="w-5 h-5 text-yellow-800 dark:text-yellow-300 stroke-[2.2] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>
                        <strong>Tips:</strong> Anda juga dapat mengganti tema kapan saja melalui tombol matahari / bulan di bagian atas navigasi (navbar) dengan sekali klik!
                    </span>
                </div>
            </div>

            <!-- TAB 3: KEAMANAN KATA SANDI -->
            <div x-show="activeTab === 'security'" class="neo-box p-6 bg-white dark:bg-[#18181B] space-y-6" style="display: none;"
                 x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                <div class="border-b-2 border-black dark:border-zinc-700 pb-4">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white">Ganti Kata Sandi</h2>
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mt-1">
                        Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap terlindungi.
                    </p>
                </div>

                <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Password Saat Ini -->
                    <div>
                        <label for="current_password" class="block font-black text-sm mb-1.5 text-gray-900 dark:text-white">
                            Kata Sandi Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showCurrent ? 'text' : 'password'" id="current_password" name="current_password" 
                                   required 
                                   class="neo-input pr-10 @error('current_password') border-red-500 @enderror"
                                   placeholder="••••••••">
                            <button type="button" @click="showCurrent = !showCurrent" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-black dark:hover:text-white">
                                <svg x-show="!showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs font-bold text-red-600 dark:text-red-400 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label for="password" class="block font-black text-sm mb-1.5 text-gray-900 dark:text-white">
                            Kata Sandi Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'" id="password" name="password" 
                                   required 
                                   class="neo-input pr-10 @error('password') border-red-500 @enderror"
                                   placeholder="Minimal 8 karakter">
                            <button type="button" @click="showNew = !showNew" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-black dark:hover:text-white">
                                <svg x-show="!showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs font-bold text-red-600 dark:text-red-400 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label for="password_confirmation" class="block font-black text-sm mb-1.5 text-gray-900 dark:text-white">
                            Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" 
                                   required 
                                   class="neo-input pr-10"
                                   placeholder="Ulangi kata sandi baru">
                            <button type="button" @click="showConfirm = !showConfirm" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-black dark:hover:text-white">
                                <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="pt-3 border-t-2 border-black dark:border-zinc-700 flex justify-end">
                        <button type="submit" class="neo-btn bg-[#FF5252] text-white hover:bg-red-600 px-6 py-2.5 text-sm">
                            <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
