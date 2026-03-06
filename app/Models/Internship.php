<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Internship extends Model
{
    // ── Status constants (state machine) ──
    const STATUS_DRAFT                  = 'draft';
    const STATUS_SUBMITTED              = 'submitted';
    const STATUS_WAITING_REQUEST_LETTER = 'waiting_request_letter';
    const STATUS_REQUEST_LETTER_UPLOADED= 'request_letter_uploaded';
    const STATUS_UNDER_REVIEW           = 'under_review';
    const STATUS_APPROVED               = 'approved';
    const STATUS_REJECTED               = 'rejected';    const STATUS_SENT_TO_STUDENT         = 'sent_to_student';    const STATUS_SUPERVISOR_ASSIGNED    = 'supervisor_assigned';
    const STATUS_ACCEPTANCE_LETTER_READY= 'acceptance_letter_ready';
    const STATUS_ONGOING                = 'ongoing';
    const STATUS_COMPLETED              = 'completed';
    const STATUS_GRADED                 = 'graded';
    const STATUS_CLOSED                 = 'closed';

    /**
     * Allowed transitions: current_status => [allowed next statuses]
     */
    const TRANSITIONS = [
        self::STATUS_DRAFT                   => [self::STATUS_SUBMITTED],
        self::STATUS_SUBMITTED               => [self::STATUS_WAITING_REQUEST_LETTER],
        self::STATUS_WAITING_REQUEST_LETTER  => [self::STATUS_REQUEST_LETTER_UPLOADED],
        self::STATUS_REQUEST_LETTER_UPLOADED => [self::STATUS_UNDER_REVIEW],
        self::STATUS_UNDER_REVIEW            => [self::STATUS_APPROVED, self::STATUS_REJECTED],
        self::STATUS_APPROVED                => [self::STATUS_SENT_TO_STUDENT],
        self::STATUS_SENT_TO_STUDENT         => [self::STATUS_SUPERVISOR_ASSIGNED],
        self::STATUS_REJECTED                => [self::STATUS_SUBMITTED], // revise & resubmit
        self::STATUS_SUPERVISOR_ASSIGNED     => [self::STATUS_ACCEPTANCE_LETTER_READY],
        self::STATUS_ACCEPTANCE_LETTER_READY => [self::STATUS_ONGOING],
        self::STATUS_ONGOING                 => [self::STATUS_COMPLETED],
        self::STATUS_COMPLETED               => [self::STATUS_GRADED],
        self::STATUS_GRADED                  => [self::STATUS_CLOSED],
        self::STATUS_CLOSED                  => [],
    ];

    const STATUS_LABELS = [
        self::STATUS_DRAFT                   => 'Draft',
        self::STATUS_SUBMITTED               => 'Diajukan',
        self::STATUS_WAITING_REQUEST_LETTER  => 'Menunggu Surat Permohonan',
        self::STATUS_REQUEST_LETTER_UPLOADED => 'Surat Permohonan Diupload',
        self::STATUS_UNDER_REVIEW            => 'Sedang Direview',
        self::STATUS_APPROVED                => 'Disetujui',
        self::STATUS_SENT_TO_STUDENT         => 'Surat Terkirim ke Mahasiswa',
        self::STATUS_REJECTED                => 'Ditolak',
        self::STATUS_SUPERVISOR_ASSIGNED     => 'Pembimbing Ditetapkan',
        self::STATUS_ACCEPTANCE_LETTER_READY => 'Surat Penerimaan Siap',
        self::STATUS_ONGOING                 => 'Magang Berjalan',
        self::STATUS_COMPLETED               => 'Magang Selesai',
        self::STATUS_GRADED                  => 'Nilai Diinput',
        self::STATUS_CLOSED                  => 'Selesai / Closed',
    ];

    const STATUS_COLORS = [
        self::STATUS_DRAFT                   => 'gray',
        self::STATUS_SUBMITTED               => 'blue',
        self::STATUS_WAITING_REQUEST_LETTER  => 'yellow',
        self::STATUS_REQUEST_LETTER_UPLOADED => 'indigo',
        self::STATUS_UNDER_REVIEW            => 'orange',
        self::STATUS_APPROVED                => 'green',
        self::STATUS_SENT_TO_STUDENT         => 'sky',
        self::STATUS_REJECTED                => 'red',
        self::STATUS_SUPERVISOR_ASSIGNED     => 'teal',
        self::STATUS_ACCEPTANCE_LETTER_READY => 'cyan',
        self::STATUS_ONGOING                 => 'emerald',
        self::STATUS_COMPLETED               => 'lime',
        self::STATUS_GRADED                  => 'violet',
        self::STATUS_CLOSED                  => 'slate',
    ];

    protected $fillable = [
        'mahasiswa_id',
        'semester_id',
        'semester_mahasiswa',
        'instansi',
        'alamat_instansi',
        'posisi',
        'periode_mulai',
        'periode_selesai',
        'deskripsi',
        'pembimbing_lapangan_nama',
        'pembimbing_lapangan_email',
        'pembimbing_lapangan_phone',
        'dokumen_pendukung_path',
        'status',
        'supervisor_dosen_id',
        'supervisor_assigned_at',
        'converted_sks',
        'request_letter_generated_path',
        'request_letter_signed_path',
        'acceptance_letter_path',
        'nomor_surat',
        'admin_final_pdf_path',
        'admin_signed_pdf_path',
        'sent_to_student_at',
        'sent_by',
        'date_changed_by',
        'date_changed_at',
        'date_change_reason',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'rejected_at',
        'revision_no',
        'admin_note',
    ];

    protected $casts = [
        'periode_mulai'          => 'date',
        'periode_selesai'        => 'date',
        'supervisor_assigned_at' => 'datetime',
        'approved_at'            => 'datetime',
        'rejected_at'            => 'datetime',
        'sent_to_student_at'     => 'datetime',
        'date_changed_at'        => 'datetime',
        'converted_sks'          => 'integer',
        'revision_no'            => 'integer',
    ];

    // ── Relationships ──

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function supervisorDosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'supervisor_dosen_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function dateChangedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'date_changed_by');
    }

    public function courseMappings(): HasMany
    {
        return $this->hasMany(InternshipCourseMapping::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(InternshipRevision::class)->orderBy('revision_no');
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(InternshipLogbook::class)->orderBy('tanggal', 'desc');
    }

    public function krsEntries(): HasMany
    {
        return $this->hasMany(Krs::class, 'internship_id');
    }

    // ── Accessors ──

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function getStatusBadgeAttribute(): string
    {
        $map = [
            self::STATUS_DRAFT                   => ['bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-300',         'draft',           'Draft'],
            self::STATUS_SUBMITTED               => ['bg-blue-100 text-blue-700 ring-1 ring-inset ring-blue-200',          'send',            'Diajukan'],
            self::STATUS_WAITING_REQUEST_LETTER  => ['bg-amber-100 text-amber-700 ring-1 ring-inset ring-amber-200',       'mail',            'Menunggu Surat'],
            self::STATUS_REQUEST_LETTER_UPLOADED => ['bg-indigo-100 text-indigo-700 ring-1 ring-inset ring-indigo-200',    'attach_file',     'Surat Diupload'],
            self::STATUS_UNDER_REVIEW            => ['bg-orange-100 text-orange-700 ring-1 ring-inset ring-orange-200',    'manage_search',   'Sedang Direview'],
            self::STATUS_APPROVED                => ['bg-green-100 text-green-700 ring-1 ring-inset ring-green-200',       'check_circle',    'Disetujui'],
            self::STATUS_SENT_TO_STUDENT         => ['bg-sky-100 text-sky-700 ring-1 ring-inset ring-sky-200',             'forward_to_inbox', 'Surat Terkirim'],
            self::STATUS_REJECTED                => ['bg-red-100 text-red-700 ring-1 ring-inset ring-red-200',             'cancel',          'Ditolak'],
            self::STATUS_SUPERVISOR_ASSIGNED     => ['bg-teal-100 text-teal-700 ring-1 ring-inset ring-teal-200',          'supervisor_account', 'Pembimbing Ditetapkan'],
            self::STATUS_ACCEPTANCE_LETTER_READY => ['bg-cyan-100 text-cyan-700 ring-1 ring-inset ring-cyan-200',          'description',     'Surat Siap'],
            self::STATUS_ONGOING                 => ['bg-emerald-100 text-emerald-700 ring-1 ring-inset ring-emerald-200', 'play_circle',     'Magang Berjalan'],
            self::STATUS_COMPLETED               => ['bg-lime-100 text-lime-700 ring-1 ring-inset ring-lime-200',          'flag',            'Selesai'],
            self::STATUS_GRADED                  => ['bg-violet-100 text-violet-700 ring-1 ring-inset ring-violet-200',    'grade',           'Nilai Diinput'],
            self::STATUS_CLOSED                  => ['bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-300',       'lock',            'Closed'],
        ];

        [$classes, $icon, $label] = $map[$this->status] ?? ['bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-300', 'circle', ucfirst($this->status)];

        return "<span class=\"inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {$classes}\"><span class=\"material-symbols-outlined\" style=\"font-size:13px;line-height:1\">{$icon}</span>{$label}</span>";
    }

    // ── State Machine Helpers ──

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::TRANSITIONS[$this->status] ?? []);
    }

    public function transitionTo(string $newStatus): void
    {
        if (!$this->canTransitionTo($newStatus)) {
            throw new \LogicException("Cannot transition from '{$this->status}' to '{$newStatus}'.");
        }
        $this->update(['status' => $newStatus]);
    }

    public function isActive(): bool
    {
        return in_array($this->status, [
            self::STATUS_ONGOING,
            self::STATUS_SUPERVISOR_ASSIGNED,
            self::STATUS_ACCEPTANCE_LETTER_READY,
        ]);
    }

    public function isOngoing(): bool
    {
        return $this->status === self::STATUS_ONGOING;
    }

    public function isEditable(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED]);
    }

    /**
     * Total SKS mapped from internship course conversions.
     */
    public function getTotalMappedSksAttribute(): int
    {
        return $this->courseMappings()->sum('sks');
    }
}
