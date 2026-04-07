<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload File – S3 Storage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0f1117;
            --surface: #1a1d2e;
            --surface2: #232640;
            --border: #2e3155;
            --accent: #6c63ff;
            --accent2: #a78bfa;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --radius: 14px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        /* ── Glow background ── */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 60% 40% at 20% 10%, rgba(108,99,255,.12) 0%, transparent 60%),
                radial-gradient(ellipse 40% 30% at 80% 80%, rgba(167,139,250,.08) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* ── Header ── */
        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .page-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--accent2) 0%, var(--accent) 60%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .page-header p {
            color: var(--muted);
            margin-top: .5rem;
            font-size: .95rem;
        }

        /* ── Drop Zone ── */
        .drop-zone {
            border: 2px dashed var(--border);
            border-radius: var(--radius);
            padding: 3rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            background: var(--surface);
            position: relative;
        }
        .drop-zone:hover, .drop-zone.drag-over {
            border-color: var(--accent);
            background: rgba(108,99,255,.07);
            box-shadow: 0 0 0 4px rgba(108,99,255,.10);
        }
        .drop-zone input[type="file"] {
            position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
        }
        .drop-icon {
            font-size: 2.5rem;
            color: var(--accent2);
            margin-bottom: 1rem;
            display: block;
        }
        .drop-zone h3 { font-size: 1.1rem; font-weight: 600; color: var(--text); }
        .drop-zone p { color: var(--muted); font-size: .85rem; margin-top: .4rem; }

        /* ── Preview list (before upload) ── */
        .preview-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .preview-item {
            background: var(--surface2);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            border: 1px solid var(--border);
            transition: transform .2s;
        }
        .preview-item:hover { transform: translateY(-2px); }
        .preview-thumb {
            width: 100%; aspect-ratio: 1;
            object-fit: cover;
            display: block;
        }
        .preview-doc {
            width: 100%; aspect-ratio: 1;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            font-size: 2rem;
            color: var(--accent2);
            background: var(--surface);
        }
        .preview-name {
            font-size: .72rem;
            color: var(--muted);
            padding: .4rem .5rem;
            word-break: break-word;
        }
        .preview-remove {
            position: absolute; top: 5px; right: 5px;
            background: rgba(239,68,68,.85);
            border: none; border-radius: 50%;
            width: 22px; height: 22px;
            cursor: pointer; color: #fff; font-size: .75rem;
            display: flex; align-items: center; justify-content: center;
            transition: transform .15s;
        }
        .preview-remove:hover { transform: scale(1.15); }

        /* ── Upload button ── */
        .btn {
            padding: .75rem 1.75rem;
            border-radius: 10px;
            font-size: .95rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all .2s;
            display: inline-flex; align-items: center; gap: .5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, #a78bfa 100%);
            color: #fff;
            box-shadow: 0 4px 20px rgba(108,99,255,.3);
        }
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 25px rgba(108,99,255,.45);
        }
        .btn-primary:disabled {
            opacity: .5; cursor: not-allowed;
        }
        .btn-danger { background: rgba(239,68,68,.15); color: var(--danger); border: 1px solid rgba(239,68,68,.3); }
        .btn-danger:hover { background: rgba(239,68,68,.25); }

        .upload-actions {
            margin-top: 1.5rem;
            display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
        }

        /* ── Progress bar ── */
        .progress-wrap { display: none; flex: 1; min-width: 160px; }
        .progress-wrap.show { display: block; }
        .progress-bar-bg {
            height: 6px; border-radius: 3px; background: var(--surface2);
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            border-radius: 3px;
            width: 0;
            transition: width .3s ease;
        }
        .progress-text { font-size: .78rem; color: var(--muted); margin-top: .3rem; }

        /* ── Toast ── */
        #toast-container {
            position: fixed; top: 1.5rem; right: 1.5rem; z-index: 9999;
            display: flex; flex-direction: column; gap: .6rem;
        }
        .toast {
            padding: .85rem 1.2rem;
            border-radius: 10px;
            font-size: .88rem;
            font-weight: 500;
            display: flex; align-items: center; gap: .6rem;
            box-shadow: 0 8px 30px rgba(0,0,0,.4);
            animation: slideIn .25s ease;
            max-width: 340px;
        }
        .toast.success { background: rgba(16,185,129,.15); border: 1px solid rgba(16,185,129,.35); color: #6ee7b7; }
        .toast.error   { background: rgba(239,68,68,.15);  border: 1px solid rgba(239,68,68,.35);  color: #fca5a5; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }

        /* ── Gallery ── */
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 2.5rem 0 1rem;
            display: flex; align-items: center; gap: .6rem;
            color: var(--text);
        }
        .section-title::after {
            content: ''; flex: 1;
            height: 1px; background: var(--border);
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1rem;
        }
        .gallery-card {
            background: var(--surface);
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
            transition: all .2s;
            position: relative;
        }
        .gallery-card:hover {
            border-color: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(108,99,255,.15);
        }
        .gallery-thumb {
            width: 100%; aspect-ratio: 1;
            object-fit: cover; display: block;
            cursor: pointer;
        }
        .gallery-doc-icon {
            width: 100%; aspect-ratio: 1;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: .5rem;
            background: var(--surface2);
            cursor: pointer;
        }
        .gallery-doc-icon i { font-size: 2.5rem; color: var(--accent2); }
        .gallery-doc-icon span { font-size: .7rem; color: var(--muted); font-weight: 600; text-transform: uppercase; }

        .gallery-info {
            padding: .75rem;
        }
        .gallery-name {
            font-size: .8rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .gallery-meta {
            font-size: .72rem;
            color: var(--muted);
            margin-top: .2rem;
            display: flex; justify-content: space-between;
        }

        .gallery-actions {
            display: flex; gap: .5rem;
            padding: 0 .75rem .75rem;
        }
        .gallery-btn {
            flex: 1;
            padding: .4rem;
            border-radius: 7px;
            font-size: .75rem;
            font-weight: 600;
            border: 1px solid;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-flex; align-items: center; justify-content: center; gap: .3rem;
            transition: all .15s;
        }
        .gallery-btn-view {
            border-color: rgba(108,99,255,.4);
            color: var(--accent2);
            background: rgba(108,99,255,.08);
        }
        .gallery-btn-view:hover { background: rgba(108,99,255,.18); }
        .gallery-btn-del {
            border-color: rgba(239,68,68,.3);
            color: var(--danger);
            background: rgba(239,68,68,.08);
        }
        .gallery-btn-del:hover { background: rgba(239,68,68,.18); }

        /* ── Folder badges ── */
        .badge {
            font-size: .65rem; font-weight: 700;
            padding: .2rem .5rem; border-radius: 100px;
            text-transform: uppercase; letter-spacing: .03em;
        }
        .badge-image    { background: rgba(16,185,129,.15); color: #6ee7b7; }
        .badge-document { background: rgba(59,130,246,.15); color: #93c5fd; }
        .badge-other    { background: rgba(245,158,11,.15); color: #fcd34d; }

        /* ── Modal lightbox ── */
        #lightbox {
            display: none; position: fixed; inset: 0; z-index: 9998;
            background: rgba(0,0,0,.85);
            align-items: center; justify-content: center;
            padding: 1rem;
        }
        #lightbox.open { display: flex; }
        #lightbox img {
            max-width: 90vw; max-height: 90vh;
            border-radius: 10px;
            object-fit: contain;
        }
        #lightbox-close {
            position: fixed; top: 1rem; right: 1.25rem;
            background: rgba(255,255,255,.1);
            border: none; color: #fff;
            border-radius: 50%; width: 40px; height: 40px;
            cursor: pointer; font-size: 1.2rem;
            display: flex; align-items: center; justify-content: center;
            transition: background .15s;
        }
        #lightbox-close:hover { background: rgba(255,255,255,.2); }

        /* ── Empty state ── */
        .empty {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--muted);
        }
        .empty i { font-size: 3rem; opacity: .4; }
        .empty p { margin-top: .75rem; font-size: .95rem; }

        /* ── Pagination ── */
        .pagination { display: flex; gap: .4rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap; }
        .page-link {
            padding: .4rem .85rem; border-radius: 8px; font-size: .85rem;
            border: 1px solid var(--border); color: var(--muted);
            text-decoration: none; background: var(--surface);
            transition: all .15s;
        }
        .page-link:hover, .page-link.active {
            background: var(--accent); color: #fff; border-color: var(--accent);
        }

        @media (max-width: 600px) {
            .gallery { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
            .preview-list { grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); }
        }
    </style>
</head>
<body>

<div id="toast-container"></div>

<!-- Lightbox for image preview -->
<div id="lightbox">
    <button id="lightbox-close" onclick="closeLightbox()"><i class="fas fa-times"></i></button>
    <img id="lightbox-img" src="" alt="Preview">
</div>

<div class="container">

    {{-- ── Header ── --}}
    <div class="page-header">
        <h1><i class="fas fa-cloud-upload-alt" style="font-size:1.8rem; -webkit-text-fill-color: #a78bfa;"></i> S3 File Manager</h1>
        <p>Upload gambar, dokumen, dan semua jenis file langsung ke Amazon S3.</p>
    </div>

    {{-- ── Flash messages ── --}}
    @if(session('success'))
        <div class="toast success" style="position:relative;max-width:100%;margin-bottom:1rem;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="toast error" style="position:relative;max-width:100%;margin-bottom:1rem;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- ── Upload Zone ── --}}
    <div class="drop-zone" id="dropZone">
        <input type="file" id="fileInput" name="files[]" multiple accept="*/*">
        <span class="drop-icon"><i class="fas fa-file-arrow-up"></i></span>
        <h3>Seret & lepas file di sini, atau klik untuk memilih</h3>
        <p>Semua tipe file diizinkan · Maksimal 5 MB per file · Maksimal 10 file sekaligus</p>
    </div>

    {{-- ── Pre-upload preview ── --}}
    <div class="preview-list" id="previewList"></div>

    {{-- ── Upload actions ── --}}
    <div class="upload-actions" id="uploadActions" style="display:none;">
        <button class="btn btn-primary" id="uploadBtn" onclick="doUpload()">
            <i class="fas fa-cloud-upload-alt"></i>
            Upload ke S3
        </button>
        <div class="progress-wrap" id="progressWrap">
            <div class="progress-bar-bg"><div class="progress-bar-fill" id="progressFill"></div></div>
            <div class="progress-text" id="progressText">Mengunggah…</div>
        </div>
        <button class="btn btn-danger" onclick="clearFiles()">
            <i class="fas fa-trash"></i> Batal
        </button>
    </div>

    {{-- ── Gallery ── --}}
    <div class="section-title">
        <i class="fas fa-photo-film" style="color:var(--accent2)"></i>
        File Tersimpan di S3
        <span style="font-weight:400;font-size:.85rem;color:var(--muted);">({{ $uploads->total() }} file)</span>
    </div>

    @if($uploads->isEmpty())
        <div class="empty">
            <i class="fas fa-folder-open"></i>
            <p>Belum ada file yang diunggah.</p>
        </div>
    @else
        <div class="gallery">
            @foreach($uploads as $file)
                @php
                    $isImage   = str_starts_with($file->mime_type, 'image/');
                    $isDoc     = in_array($file->extension, ['pdf','doc','docx','xls','xlsx','ppt','pptx','txt','csv']);
                    $folderKey = $isImage ? 'image' : ($isDoc ? 'document' : 'other');
                    $badgeClass= ['image'=>'badge-image','document'=>'badge-document','other'=>'badge-other'][$folderKey];
                    $badgeLabel= ['image'=>'Gambar','document'=>'Dokumen','other'=>'Lainnya'][$folderKey];
                    $icon      = $isImage ? '' : ($file->extension === 'pdf'
                        ? 'fa-file-pdf' : (in_array($file->extension, ['doc','docx']) ? 'fa-file-word'
                        : (in_array($file->extension, ['xls','xlsx']) ? 'fa-file-excel'
                        : (in_array($file->extension, ['ppt','pptx']) ? 'fa-file-powerpoint' : 'fa-file'))));
                @endphp
                <div class="gallery-card" id="card-{{ $file->id }}">
                    @if($isImage)
                        <img class="gallery-thumb"
                             src="{{ $file->url }}"
                             alt="{{ $file->original_name }}"
                             onclick="openLightbox('{{ $file->url }}')">
                    @else
                        <div class="gallery-doc-icon"
                             onclick="window.open('{{ $file->url }}', '_blank')">
                            <i class="fas {{ $icon }}"></i>
                            <span>{{ strtoupper($file->extension) }}</span>
                        </div>
                    @endif

                    <div class="gallery-info">
                        <div style="display:flex;justify-content:space-between;align-items:start;gap:.4rem;">
                            <span class="gallery-name" title="{{ $file->original_name }}">
                                {{ $file->original_name }}
                            </span>
                            <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        </div>
                        <div class="gallery-meta">
                            <span>{{ $file->human_size }}</span>
                            <span>{{ $file->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="gallery-actions">
                        <a href="{{ $file->url }}"
                           target="_blank"
                           class="gallery-btn gallery-btn-view">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <button class="gallery-btn gallery-btn-del"
                                onclick="deleteFile({{ $file->id }}, '{{ addslashes($file->original_name) }}')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($uploads->lastPage() > 1)
            <div class="pagination">
                @foreach($uploads->links()->elements as $element)
                    @if(is_array($element))
                        @foreach($element as $page => $url)
                            <a href="{{ $url }}"
                               class="page-link {{ $page == $uploads->currentPage() ? 'active' : '' }}">
                                {{ $page }}
                            </a>
                        @endforeach
                    @endif
                @endforeach
            </div>
        @endif
    @endif

</div>{{-- /container --}}

<script>
    /* ── File selection & pre-upload preview ── */
    const fileInput   = document.getElementById('fileInput');
    const dropZone    = document.getElementById('dropZone');
    const previewList = document.getElementById('previewList');
    const uploadActions = document.getElementById('uploadActions');
    let selectedFiles = [];

    fileInput.addEventListener('change', e => handleFiles(Array.from(e.target.files)));

    dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        handleFiles(Array.from(e.dataTransfer.files));
    });

    function handleFiles(files) {
        const MAX_MB = 5;
        const valid  = files.filter(f => {
            if (f.size > MAX_MB * 1024 * 1024) {
                showToast(`${f.name} melebihi batas ${MAX_MB} MB.`, 'error');
                return false;
            }
            return true;
        });

        selectedFiles = [...selectedFiles, ...valid].slice(0, 10);
        renderPreviews();
    }

    function renderPreviews() {
        previewList.innerHTML = '';
        uploadActions.style.display = selectedFiles.length ? 'flex' : 'none';

        selectedFiles.forEach((file, idx) => {
            const isImage = file.type.startsWith('image/');
            const item    = document.createElement('div');
            item.className = 'preview-item';

            const ext = file.name.split('.').pop().toLowerCase();
            const iconMap = {
                pdf: 'fa-file-pdf', doc: 'fa-file-word', docx: 'fa-file-word',
                xls: 'fa-file-excel', xlsx: 'fa-file-excel',
                ppt: 'fa-file-powerpoint', pptx: 'fa-file-powerpoint',
            };
            const iconClass = iconMap[ext] || 'fa-file';

            if (isImage) {
                const img = document.createElement('img');
                img.className = 'preview-thumb';
                img.src = URL.createObjectURL(file);
                item.appendChild(img);
            } else {
                const box  = document.createElement('div');
                box.className = 'preview-doc';
                box.innerHTML = `<i class="fas ${iconClass}"></i><small style="font-size:.65rem;text-transform:uppercase;color:var(--muted)">${ext}</small>`;
                item.appendChild(box);
            }

            const name = document.createElement('div');
            name.className = 'preview-name';
            name.textContent = file.name;
            item.appendChild(name);

            const rmBtn = document.createElement('button');
            rmBtn.className = 'preview-remove';
            rmBtn.innerHTML = '<i class="fas fa-times"></i>';
            rmBtn.onclick = () => { selectedFiles.splice(idx, 1); renderPreviews(); };
            item.appendChild(rmBtn);

            previewList.appendChild(item);
        });
    }

    function clearFiles() {
        selectedFiles = [];
        fileInput.value = '';
        renderPreviews();
    }

    /* ── Upload ── */
    async function doUpload() {
        if (!selectedFiles.length) return;

        const btn  = document.getElementById('uploadBtn');
        const wrap = document.getElementById('progressWrap');
        const fill = document.getElementById('progressFill');
        const txt  = document.getElementById('progressText');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengunggah…';
        wrap.classList.add('show');

        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        selectedFiles.forEach(f => formData.append('files[]', f));

        try {
            const res = await fetch('{{ route('uploads.store') }}', {
                method:  'POST',
                headers: { 'Accept': 'application/json' },
                body:    formData,
            });

            // fake progress while awaiting
            let p = 0;
            const iv = setInterval(() => {
                p = Math.min(p + Math.random() * 15, 90);
                fill.style.width = p + '%';
                txt.textContent = `Mengunggah… ${Math.floor(p)}%`;
            }, 200);

            const data = await res.json();
            clearInterval(iv);
            fill.style.width = '100%';
            txt.textContent = 'Selesai!';

            if (res.ok && data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                const msg = data.message || data.error || 'Upload gagal.';
                showToast(msg, 'error');
                resetBtn(btn, wrap);
            }
        } catch (e) {
            showToast('Terjadi kesalahan jaringan.', 'error');
            resetBtn(btn, wrap);
        }
    }

    function resetBtn(btn, wrap) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Upload ke S3';
        wrap.classList.remove('show');
    }

    /* ── Delete ── */
    async function deleteFile(id, name) {
        if (!confirm(`Hapus file "${name}" dari S3?\nTindakan ini tidak dapat dibatalkan.`)) return;

        const token = document.querySelector('meta[name="csrf-token"]').content;

        try {
            const res = await fetch(`/uploads/${id}`, {
                method:  'DELETE',
                headers: {
                    'Accept':           'application/json',
                    'X-CSRF-TOKEN':     token,
                    'Content-Type':     'application/json',
                },
            });

            const data = await res.json();
            if (res.ok && data.success) {
                const card = document.getElementById(`card-${id}`);
                card.style.opacity = '0';
                card.style.transform = 'scale(.9)';
                card.style.transition = 'all .25s';
                setTimeout(() => card.remove(), 250);
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Gagal menghapus file.', 'error');
            }
        } catch {
            showToast('Terjadi kesalahan jaringan.', 'error');
        }
    }

    /* ── Lightbox ── */
    function openLightbox(url) {
        document.getElementById('lightbox-img').src = url;
        document.getElementById('lightbox').classList.add('open');
    }
    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('open');
    }
    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target === this) closeLightbox();
    });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

    /* ── Toast ── */
    function showToast(msg, type = 'success') {
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `<i class="fas ${icon}"></i> ${msg}`;
        document.getElementById('toast-container').appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity .3s'; setTimeout(() => toast.remove(), 300); }, 4000);
    }
</script>
</body>
</html>
