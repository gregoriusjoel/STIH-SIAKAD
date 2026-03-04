@extends(auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : 'layouts.app')

@section('title', $pengumuman->judul)

@section('page-title', 'Detail Pengumuman')

@section('navbar_breadcrumb')
    <nav class="flex items-center gap-3 text-sm text-white/60 font-bold tracking-tight">
        <a class="hover:text-white transition-all duration-300 flex items-center group"
            href="{{ auth()->user()->role == 'dosen' ? route('dosen.dashboard') : route('mahasiswa.dashboard') }}">
            <span class="material-symbols-outlined text-[19px] group-hover:scale-110">home</span>
        </a>
        <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
        <a class="hover:text-white transition-all duration-300" 
           href="{{ auth()->user()->role == 'dosen' ? route('dosen.pengumuman.index') : route('mahasiswa.pengumuman.index') }}">
            PENGUMUMAN
        </a>
        <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
        <span class="text-white font-black text-[13px] uppercase tracking-wider truncate max-w-[200px]">Detail</span>
    </nav>
@endsection

@section('content')
    <div class="max-w-6xl 2xl:max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{ 
        readIds: JSON.parse(localStorage.getItem('read_announcement_ids') || '[]'),
        init() {
            const id = {{ $pengumuman->id }};
            if (!this.readIds.includes(id)) {
                this.readIds.push(id);
                localStorage.setItem('read_announcement_ids', JSON.stringify(this.readIds));
            }
        }
    }">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ auth()->user()->role == 'dosen' ? route('dosen.pengumuman.index') : route('mahasiswa.pengumuman.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Kembali ke Daftar
            </a>
        </div>

        <article id="announcement-card" class="bg-white dark:bg-slate-800 rounded-[32px] shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            {{-- Header --}}
            <header class="p-8 md:p-12 border-b border-slate-50 dark:border-slate-700/50 bg-slate-50/30 dark:bg-slate-700/30">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-[10px] font-black uppercase tracking-[0.15em] leading-none">
                        {{ $pengumuman->target == 'semua' ? 'Pengumuman Global' : ($pengumuman->target == 'dosen' ? 'Khusus Dosen' : 'Khusus Mahasiswa') }}
                    </span>
                    <div class="h-4 w-px bg-slate-200 dark:bg-slate-600"></div>
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        {{ \Carbon\Carbon::parse($pengumuman->published_at)->translatedFormat('d F Y') }}
                    </div>
                </div>

                <h1 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-[1.1]">
                    {{ $pengumuman->judul }}
                </h1>

                <div class="mt-8 flex flex-wrap items-center gap-6">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[20px] fill-current">person</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Oleh</span>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 mt-1">Administrator</span>
                        </div>
                    </div>
                    <div class="h-8 w-px bg-slate-100 dark:bg-slate-700 hidden sm:block"></div>
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600">
                            <span class="material-symbols-outlined text-[20px]">schedule</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Waktu</span>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 mt-1">{{ \Carbon\Carbon::parse($pengumuman->published_at)->format('H:i') }} WIB</span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <div class="p-8 md:p-12">
                <div class="prose prose-slate prose-lg max-w-none dark:prose-invert 
                            prose-headings:font-black prose-headings:tracking-tight prose-headings:text-slate-900 dark:prose-headings:text-white 
                            prose-p:text-slate-600 dark:prose-p:text-slate-400 prose-p:leading-[1.8] prose-p:font-medium
                            prose-strong:text-slate-900 dark:prose-strong:text-white prose-strong:font-black
                            prose-img:rounded-[24px] prose-img:shadow-xl">
                    {!! $pengumuman->isi !!}
                </div>
            </div>

            {{-- Footer Info --}}
            <footer class="px-8 md:px-12 py-6 bg-slate-50/50 dark:bg-slate-700/20 border-t border-slate-50 dark:border-slate-700/50 flex flex-col md:flex-row md:items-center justify-between gap-4" data-html2canvas-ignore>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest italic">
                    &copy; {{ date('Y') }} STIH Adhyaksa - Pusat Informasi
                </p>
                <div class="flex items-center gap-4">
                    <button id="btn-share" class="flex items-center gap-2 text-xs font-black text-slate-500 hover:text-primary transition-colors uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined text-[18px] icon-share">share</span>
                        <span class="text-share">Bagikan</span>
                    </button>
                </div>
            </footer>
        </article>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.11.11/html-to-image.min.js"></script>
    <script>
        document.getElementById('btn-share').addEventListener('click', async function() {
            const btn = this;
            const textShare = btn.querySelector('.text-share');
            const footer = document.querySelector('#announcement-card footer');
            
            try {
                // UI Loading state
                btn.disabled = true;
                textShare.textContent = 'Memproses...';

                // Temporarily hide the footer so it's not captured
                const originalDisplay = footer.style.display;
                footer.style.display = 'none';

                const card = document.getElementById('announcement-card');
                const bgColor = document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff';

                // Capture the card as a blob (modern CSS supported)
                const blob = await htmlToImage.toBlob(card, {
                    pixelRatio: 2,
                    backgroundColor: bgColor,
                    style: {
                        margin: '0',
                        borderRadius: '32px'
                    }
                });

                // Restore footer visibility
                footer.style.display = originalDisplay;

                if (!blob) throw new Error('Failed to create image blob');
                
                const file = new File([blob], 'Pengumuman_STIH.png', { type: 'image/png' });
                
                // Check if share as file is supported
                if (navigator.canShare && navigator.canShare({ files: [file] })) {
                    await navigator.share({
                        files: [file],
                        title: '{{ addslashes($pengumuman->judul) }}',
                        text: 'Ada pengumuman baru dari STIH Adhyaksa: {{ addslashes($pengumuman->judul) }}'
                    });
                } else {
                    // Fallback: download the image if sharing files is not supported (e.g. some desktop browsers)
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'Pengumuman_STIH.png';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                    alert('Perangkat Anda tidak mendukung fitur berbagi gambar secara langsung. Gambar pengumuman telah diunduh ke perangkat Anda.');
                }
            } catch (error) {
                console.error('Error sharing image:', error);
                // Make sure to restore footer if error happens before restoring
                if (footer) footer.style.display = '';
                alert('Terjadi kesalahan saat memproses gambar pengumuman. Pastikan browser Anda mendukung fitur ini.');
            } finally {
                // Restore UI state
                btn.disabled = false;
                textShare.textContent = 'Bagikan';
            }
        });
    </script>
@endsection
