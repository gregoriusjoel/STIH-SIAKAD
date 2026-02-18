{{-- Example: Admin Dashboard Widget for Semester Transition Status --}}
{{-- Path: resources/views/admin/dashboard/widgets/semester-transition.blade.php --}}

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-calendar-alt me-2"></i>
            Status Transisi Semester
        </h5>
        <span class="badge bg-light text-dark" id="refresh-time">Loading...</span>
    </div>
    <div class="card-body" id="semester-status-container">
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="refreshStatus()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
        <button type="button" class="btn btn-sm btn-warning" onclick="previewTransition()" id="btn-preview">
            <i class="fas fa-eye"></i> Preview
        </button>
        <button type="button" class="btn btn-sm btn-success" onclick="processTransition()" id="btn-process" disabled>
            <i class="fas fa-play"></i> Proses Transisi Manual
        </button>
    </div>
</div>

<script>
// Auto-load on page ready
$(document).ready(function() {
    loadStatus();
    // Auto refresh every 5 minutes
    setInterval(loadStatus, 300000);
});

// Load status from API
function loadStatus() {
    $.ajax({
        url: '{{ route('admin.semester-transition.status') }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                renderStatus(response.data);
                updateRefreshTime();
            }
        },
        error: function(xhr) {
            showError('Gagal memuat status transisi');
        }
    });
}

// Render status to UI
function renderStatus(data) {
    let html = '';
    
    if (!data.has_active_semester) {
        html = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                ${data.message || 'Tidak ada semester aktif'}
            </div>
        `;
    } else {
        const current = data.current_semester;
        const next = data.next_semester;
        const isEnded = data.is_ended;
        const daysRemaining = data.days_remaining;
        
        // Current semester info
        html += `
            <div class="mb-3">
                <h6 class="text-muted mb-2">Semester Aktif</h6>
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                    <div>
                        <strong class="d-block">${current.nama_semester} ${current.tahun_ajaran}</strong>
                        <small class="text-muted">
                            ${current.tanggal_mulai} s/d ${current.tanggal_selesai}
                        </small>
                    </div>
                    <div class="text-end">
                        ${isEnded ? 
                            `<span class="badge bg-danger">Telah Berakhir</span>` : 
                            `<span class="badge bg-success">Berjalan</span>`
                        }
                        <div class="mt-1">
                            <small class="text-muted">
                                ${Math.abs(daysRemaining)} hari ${daysRemaining >= 0 ? 'lagi' : 'yang lalu'}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Next semester info
        if (next) {
            html += `
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Semester Berikutnya</h6>
                    <div class="p-3 bg-light rounded">
                        <strong class="d-block">${next.nama_semester} ${next.tahun_ajaran}</strong>
                        <small class="text-muted">Mulai: ${next.tanggal_mulai}</small>
                    </div>
                </div>
            `;
        } else {
            html += `
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    Semester berikutnya belum tersedia. Mohon tambahkan periode semester baru.
                </div>
            `;
        }
        
        // Status readiness
        if (data.ready_for_transition) {
            html += `
                <div class="alert alert-success mb-0">
                    <i class="fas fa-check-circle"></i>
                    <strong>Sistem siap melakukan transisi!</strong>
                    <div class="mt-2">
                        <small>Klik tombol "Preview" untuk melihat detail atau "Proses Transisi Manual" untuk menjalankan sekarang.</small>
                    </div>
                </div>
            `;
            $('#btn-process').prop('disabled', false);
            $('#btn-preview').prop('disabled', false);
        } else {
            html += `
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i>
                    Sistem belum siap untuk transisi. ${
                        !isEnded ? 
                        'Periode semester masih berjalan.' : 
                        'Semester berikutnya belum tersedia.'
                    }
                </div>
            `;
            $('#btn-process').prop('disabled', true);
            $('#btn-preview').prop('disabled', !isEnded);
        }
    }
    
    $('#semester-status-container').html(html);
}

// Preview transition
function previewTransition() {
    $.ajax({
        url: '{{ route('admin.semester-transition.preview') }}',
        method: 'GET',
        beforeSend: function() {
            $('#btn-preview').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        },
        success: function(response) {
            if (response.success) {
                const data = response.data;
                Swal.fire({
                    title: 'Preview Transisi Semester',
                    html: `
                        <div class="text-start">
                            <table class="table table-sm">
                                <tr>
                                    <th>Dari Semester:</th>
                                    <td>${data.current_semester.nama_semester} ${data.current_semester.tahun_ajaran}</td>
                                </tr>
                                <tr>
                                    <th>Ke Semester:</th>
                                    <td>${data.next_semester.nama_semester} ${data.next_semester.tahun_ajaran}</td>
                                </tr>
                                <tr>
                                    <th>Mahasiswa Terdampak:</th>
                                    <td><strong class="text-primary">${data.eligible_mahasiswa_count} mahasiswa</strong></td>
                                </tr>
                                <tr>
                                    <th>Estimasi Waktu:</th>
                                    <td>${data.estimated_execution_time}</td>
                                </tr>
                            </table>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Tutup'
                });
            }
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Gagal memuat preview', 'error');
        },
        complete: function() {
            $('#btn-preview').prop('disabled', false).html('<i class="fas fa-eye"></i> Preview');
        }
    });
}

// Process transition manually
function processTransition() {
    Swal.fire({
        title: 'Konfirmasi Transisi Semester',
        text: 'Apakah Anda yakin ingin menjalankan transisi semester sekarang? Proses ini akan menaikkan semester semua mahasiswa aktif.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Proses Sekarang!',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: '{{ route('admin.semester-transition.process') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                }
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            const response = result.value;
            if (response.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    html: `
                        <div class="text-start">
                            <p class="mb-3">${response.message}</p>
                            <table class="table table-sm">
                                <tr>
                                    <th>Semester Lama:</th>
                                    <td>${response.data.old_semester}</td>
                                </tr>
                                <tr>
                                    <th>Semester Baru:</th>
                                    <td>${response.data.new_semester}</td>
                                </tr>
                                <tr>
                                    <th>Mahasiswa Diupdate:</th>
                                    <td><strong class="text-success">${response.data.mahasiswa_updated} mahasiswa</strong></td>
                                </tr>
                                <tr>
                                    <th>Waktu Transisi:</th>
                                    <td>${response.data.transition_date}</td>
                                </tr>
                            </table>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reload status
                    loadStatus();
                    // Optionally reload page
                    // location.reload();
                });
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
}

// Refresh status manually
function refreshStatus() {
    loadStatus();
}

// Update refresh time display
function updateRefreshTime() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString('id-ID');
    $('#refresh-time').text(`Update: ${timeStr}`);
}

// Show error message
function showError(message) {
    $('#semester-status-container').html(`
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            ${message}
        </div>
    `);
}
</script>
