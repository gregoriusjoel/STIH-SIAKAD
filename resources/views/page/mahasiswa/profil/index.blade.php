@extends('layouts.mahasiswa')

@section('title', 'Profil Mahasiswa')
@section('page-title', 'Profil Mahasiswa')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left Column: Photo & Basic Identity --}}
        <div class="space-y-6">
            <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-6 text-center h-full" x-data="photoCropper()">
                <div class="relative w-40 h-40 mx-auto mb-6 {{ $mahasiswa->foto ? 'group cursor-pointer' : '' }}"
                    @click="{{ $mahasiswa->foto ? "openPreview('" . asset('storage/' . $mahasiswa->foto) . "')" : '' }}">
                    <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-gray-100 dark:border-slate-700 shadow-sm relative flex items-center justify-center bg-gray-100 dark:bg-slate-700 {{ $mahasiswa->foto ? 'group-hover:border-maroon' : '' }} transition-colors duration-300">
                        <img id="profile-preview" src="{{ $mahasiswa->foto ? asset('storage/' . $mahasiswa->foto) : '' }}"
                            alt="Foto Profil" class="w-full h-full object-cover {{ $mahasiswa->foto ? '' : 'hidden' }}">
                        <div id="profile-icon" class="{{ $mahasiswa->foto ? 'hidden' : '' }}">
                            <i class="fas fa-user text-6xl text-gray-400 dark:text-slate-500"></i>
                        </div>

                        {{-- Hover Overlay --}}
                        @if($mahasiswa->foto)
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center rounded-full backdrop-blur-[1px] gap-1">
                                <i class="fas fa-search-plus text-white text-2xl mb-1"></i>
                                <span class="text-white text-[10px] font-bold uppercase tracking-wider">Detail Foto</span>
                            </div>
                        @endif
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-1">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ $mahasiswa->nim }}</p>

                <form action="{{ route('mahasiswa.profil.update-foto') }}" method="POST" enctype="multipart/form-data"
                    class="mt-4" id="form-upload-foto">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block w-full">
                            <span class="sr-only">Pilih Foto</span>
                            <input type="file" name="foto" accept="image/*" required
                                @change="fileChosen" class="block w-full text-sm text-gray-500 dark:text-slate-400
                                                    bg-[#F9FAFB] dark:bg-slate-800
                                                    border border-[#E5E7EB] dark:border-slate-700 border-dashed rounded-xl px-4 py-3
                                                    focus:outline-none focus:border-maroon focus:ring-4 focus:ring-maroon/5 transition-all font-medium
                                                    file:mr-4 file:py-2 file:px-4
                                                    file:rounded-full file:border-0
                                                    file:text-xs file:font-semibold
                                                    file:bg-maroon file:text-white
                                                    hover:file:bg-maroon-hover
                                                    cursor-pointer" id="foto-input">
                        </label>

                        <div x-show="fileName" class="text-xs text-center mt-2 text-green-600 dark:text-green-400">
                            <span x-text="fileName"></span> siap diupload
                        </div>

                        {{-- Crop Modal --}}
                        <div x-show="showCropModal" x-cloak
                            class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                                <div x-show="showCropModal"
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="fixed inset-0 bg-gray-900/60 backdrop-blur-md transition-opacity" aria-hidden="true"></div>

                                <div x-show="showCropModal"
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    class="relative bg-white dark:bg-[#1a1d2e] rounded-lg text-left shadow-xl transform transition-all sm:max-w-lg w-full flex flex-col overflow-hidden">

                                    <div class="px-4 pt-5 pb-4 sm:p-6 flex-grow">
                                        <div class="text-center sm:text-left">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                                Sesuaikan Foto
                                            </h3>
                                            <div class="mt-4 w-full h-80 bg-gray-100 dark:bg-black rounded-lg relative overflow-hidden">
                                                <img id="image-to-crop" class="block max-w-full" style="max-height: 100%; opacity: 0;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-[#151725] px-4 py-3 sm:px-6 flex flex-row-reverse gap-2">
                                        <button type="button" @click="cropAndSave"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-maroon text-base font-medium text-white hover:bg-maroon-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-maroon sm:w-auto sm:text-sm">
                                            Potong & Terapkan
                                        </button>
                                        <button type="button" @click="cancelCrop"
                                            class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-2 bg-maroon text-white text-sm font-bold rounded-lg hover:bg-maroon-hover transition shadow-sm">
                        <i class="fas fa-camera mr-2"></i> Update Foto
                    </button>
                </form>

                {{-- Profile Preview Modal --}}
                <div x-show="profilePreview" x-cloak
                    class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                        <div x-show="profilePreview"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity"
                            @click="closePreview()"
                            aria-hidden="true"></div>

                        <div x-show="profilePreview"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="relative transform transition-all max-w-2xl w-full flex flex-col items-center justify-center"
                            @click.stop>

                            <button @click="closePreview()"
                                class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
                                <i class="fas fa-times text-2xl"></i>
                            </button>

                            <img :src="previewImage" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl object-contain bg-white dark:bg-slate-800">
                        </div>
                    </div>
                </div>

                @push('styles')
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
                <style>
                    .cropper-view-box,
                    .cropper-face {
                        border-radius: 50%;
                    }

                    .cropper-view-box {
                        outline: 0;
                        box-shadow: 0 0 0 1px #39f;
                    }

                    /* Ensure image fits */
                    .cropper-container {
                        width: 100%;
                        height: 100%;
                    }
                </style>
                @endpush

                @push('scripts')
                <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
                <script>
                    function photoCropper() {
                        return {
                            showCropModal: false,
                            fileName: '',
                            cropper: null,

                            // Profile Preview Logic
                            profilePreview: false,
                            previewImage: '',

                            openPreview(url) {
                                if (url && !this.showCropModal) {
                                    this.previewImage = url;
                                    this.profilePreview = true;
                                }
                            },

                            closePreview() {
                                this.profilePreview = false;
                                this.previewImage = '';
                            },

                            fileChosen(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    // Validate file format
                                    const allowedExtensions = ['.jpg', '.jpeg', '.png', '.svg', '.webp'];
                                    const fileName = file.name.toLowerCase();
                                    const isValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));

                                    if (!isValidExtension) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Format File Tidak Valid!',
                                            html: 'File yang diizinkan: <strong>JPG, JPEG, PNG, SVG, atau WEBP</strong>.<br><br>File "<strong>' + file.name + '</strong>" tidak dapat diupload.',
                                            confirmButtonColor: '#8B1538',
                                            confirmButtonText: 'OK'
                                        });
                                        event.target.value = '';
                                        this.fileName = '';
                                        return;
                                    }

                                    this.fileName = file.name;

                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        const image = document.getElementById('image-to-crop');
                                        image.src = e.target.result;

                                        this.showCropModal = true;

                                        // Use setTimeout to allow modal transition and layout to settle
                                        setTimeout(() => {
                                            if (this.cropper) {
                                                this.cropper.destroy();
                                            }

                                            image.style.opacity = '1'; // Show image once loaded

                                            this.cropper = new Cropper(image, {
                                                aspectRatio: 1,
                                                viewMode: 1,
                                                dragMode: 'move',
                                                autoCropArea: 0.8,
                                                restore: false,
                                                guides: true,
                                                center: true,
                                                highlight: false,
                                                cropBoxMovable: true,
                                                cropBoxResizable: true,
                                                toggleDragModeOnDblclick: false,
                                                minContainerWidth: 300,
                                                minContainerHeight: 300,
                                            });
                                        }, 100);
                                    };
                                    reader.readAsDataURL(file);
                                }
                            },

                            cropAndSave() {
                                if (this.cropper) {
                                    this.cropper.getCroppedCanvas().toBlob((blob) => {
                                        // 1. Create a new file
                                        const file = new File([blob], this.fileName, {
                                            type: 'image/jpeg'
                                        });

                                        // 2. Update File Input
                                        const dataTransfer = new DataTransfer();
                                        dataTransfer.items.add(file);
                                        document.getElementById('foto-input').files = dataTransfer.files;

                                        // 3. Update Preview Image Immediately
                                        const previewUrl = URL.createObjectURL(blob);
                                        const previewImg = document.getElementById('profile-preview');
                                        const previewIcon = document.getElementById('profile-icon');

                                        previewImg.src = previewUrl;
                                        previewImg.classList.remove('hidden');
                                        previewIcon.classList.add('hidden');

                                        // Close modal
                                        this.showCropModal = false;

                                        // Cleanup
                                        this.cropper.destroy();
                                        this.cropper = null;
                                    }, 'image/jpeg');
                                }
                            },

                            cancelCrop() {
                                this.showCropModal = false;
                                this.fileName = '';
                                document.getElementById('foto-input').value = ''; // Reset input
                                if (this.cropper) {
                                    this.cropper.destroy();
                                    this.cropper = null;
                                }
                            }
                        }
                    }
                </script>
                @endpush
            </div>
        </div>

        {{-- Right Column: Security --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-6 h-full">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 dark:border-slate-700 pb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <i class="fas fa-lock text-maroon text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Keamanan Akun</h3>
                        <p class="text-sm text-gray-500 dark:text-slate-400">Ganti password akun Anda</p>
                    </div>
                </div>

                <form action="{{ route('mahasiswa.profil.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Password
                                Saat Ini</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="current_password" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-700 focus:ring-2 focus:ring-maroon focus:border-transparent bg-white dark:bg-slate-800 text-gray-800 dark:text-white"
                                    placeholder="Masukkan password lama">
                                <button type="button" @click="show = !show"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Password
                                    Baru</label>
                                <div class="relative" x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" name="new_password" required
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-700 focus:ring-2 focus:ring-maroon focus:border-transparent bg-white dark:bg-slate-800 text-gray-800 dark:text-white"
                                        placeholder="Minimal 8 karakter">
                                    <button type="button" @click="show = !show"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Konfirmasi
                                    Password Baru</label>
                                <div class="relative" x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" name="new_password_confirmation" required
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-700 focus:ring-2 focus:ring-maroon focus:border-transparent bg-white dark:bg-slate-800 text-gray-800 dark:text-white"
                                        placeholder="Ulangi password baru">
                                    <button type="button" @click="show = !show"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="px-6 py-3 bg-maroon text-white font-bold rounded-lg hover:bg-maroon-hover transition shadow-md hover:shadow-lg flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Simpan Password Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bottom Section: Account Info (Full Width) --}}
    <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-6">
        <h4
            class="text-sm font-bold text-gray-400 dark:text-slate-500 uppercase mb-6 border-b border-gray-100 dark:border-slate-700 pb-2">
            Informasi Akun
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
            <div class="flex flex-col gap-1">
                <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Program Studi</span>
                <span class="font-semibold text-gray-800 dark:text-white text-lg">{{ $mahasiswa->prodi }}</span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Status</span>
                <div>
                    @php
                    $displayStatus = 'Aktif';
                    @endphp
                    <span class="inline-block px-3 py-1 rounded text-xs font-bold bg-green-100 text-green-800">
                        {{ $displayStatus }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Email Saat Ini</span>
                <span class="font-semibold text-gray-800 dark:text-white text-lg truncate"
                    title="{{ $user->email }}">{{ $user->email }}</span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">No. HP</span>
                <span class="font-semibold text-gray-800 dark:text-white text-lg">{{ $mahasiswa->no_hp ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection