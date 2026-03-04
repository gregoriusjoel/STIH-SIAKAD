<?php

namespace App\Support;

/**
 * Mapping: jenis pengajuan → template DOCX, field dinamis, placeholder merge.
 *
 * Placeholder di dokumen DOCX menggunakan format ${nama_var} (PhpWord default).
 * Semua placeholder ${...} yang ada di file .docx HARUS terdaftar di 'placeholders'.
 *
 * Cara baca: 'placeholders' adalah array ['nama_placeholder' => 'sumber_data']
 *   - Jika value berupa callable → diterapkan pada objek $context (array data mahasiswa + payload)
 *   - Jika value berupa string dimulai '@' → ambil dari payload_template
 *   - Jika value berupa string biasa → ambil dari context[$value]
 */
class LetterTemplateConfig
{
    /**
     * Daftar semua jenis pengajuan yang didukung.
     * Key = jenis (string disimpan di DB).
     */
    public static function all(): array
    {
        return [
            'surat_aktif'     => static::suratAktif(),
            'cuti'            => static::cutiAkademik(),
            'dispensasi'      => static::dispensasi(),
            'izin_penelitian' => static::izinPenelitian(),
        ];
    }

    /** Kembalikan config satu jenis, atau null jika tidak ditemukan. */
    public static function get(string $jenis): ?array
    {
        return static::all()[$jenis] ?? null;
    }

    /** Daftar untuk dropdown pilihan mahasiswa. */
    public static function options(): array
    {
        return collect(static::all())
            ->map(fn($cfg) => $cfg['label'])
            ->all();
    }

    // ──────────────────────────────────────────────────────────────
    //  Jenis 1: Surat Keterangan Aktif Kuliah
    // ──────────────────────────────────────────────────────────────
    private static function suratAktif(): array
    {
        return [
            'label'    => 'Surat Keterangan Aktif Kuliah',
            'template' => 'Surat Permohonan Penerbitan Surat Keterangan Aktif Mahasiswa.docx',
            'icon'     => 'fa-file-signature',
            'color'    => 'blue',

            // Field dinamis yang muncul di form mahasiswa
            'fields' => [
                [
                    'name'        => 'tujuan',
                    'label'       => 'Tujuan Surat',
                    'type'        => 'text',
                    'placeholder' => 'Contoh: Keperluan Beasiswa KIP Kuliah',
                    'required'    => true,
                    'rules'       => 'required|string|max:255',
                ],
                [
                    'name'        => 'instansi_tujuan',
                    'label'       => 'Instansi / Lembaga Tujuan',
                    'type'        => 'text',
                    'placeholder' => 'Contoh: Kementerian Pendidikan RI',
                    'required'    => false,
                    'rules'       => 'nullable|string|max:255',
                ],
            ],

            // Mapping placeholder DOCX → source data
            // Kunci = nama placeholder di ${...}, value = key dari $context
            'placeholders' => [
                'nama'           => 'nama',
                'nim'            => 'nim',
                'prodi'          => 'prodi',
                'fakultas'       => 'fakultas',
                'semester'       => 'semester',
                'tahun_ajaran'   => 'tahun_ajaran',
                'tanggal'        => 'tanggal',
                'tujuan'         => '@tujuan',
                'instansi_tujuan'=> '@instansi_tujuan',
                'alamat'         => 'alamat',
                'no_hp'          => 'no_hp',
            ],
        ];
    }

    // ──────────────────────────────────────────────────────────────
    //  Jenis 2: Cuti Akademik
    // ──────────────────────────────────────────────────────────────
    private static function cutiAkademik(): array
    {
        return [
            'label'    => 'Cuti Akademik',
            'template' => 'Surat Permohonan Cuti Akademik.docx',
            'icon'     => 'fa-pause-circle',
            'color'    => 'orange',

            'fields' => [
                [
                    'name'        => 'semester_cuti',
                    'label'       => 'Semester yang Diajukan Cuti',
                    'type'        => 'text',
                    'placeholder' => 'Contoh: Semester 4 (Genap 2025/2026)',
                    'required'    => true,
                    'rules'       => 'required|string|max:100',
                ],
                [
                    'name'        => 'tanggal_mulai_cuti',
                    'label'       => 'Tanggal Mulai Cuti',
                    'type'        => 'date',
                    'required'    => true,
                    'rules'       => 'required|date',
                ],
                [
                    'name'        => 'tanggal_selesai_cuti',
                    'label'       => 'Tanggal Selesai Cuti',
                    'type'        => 'date',
                    'required'    => true,
                    'rules'       => 'required|date|after_or_equal:payload_template.tanggal_mulai_cuti',
                ],
                [
                    'name'        => 'alasan_cuti',
                    'label'       => 'Alasan Cuti',
                    'type'        => 'textarea',
                    'placeholder' => 'Jelaskan alasan mengajukan cuti akademik...',
                    'required'    => true,
                    'rules'       => 'required|string|max:1000',
                ],
            ],

            'placeholders' => [
                'nama'               => 'nama',
                'nim'                => 'nim',
                'prodi'              => 'prodi',
                'fakultas'           => 'fakultas',
                'semester'           => 'semester',
                'tahun_ajaran'       => 'tahun_ajaran',
                'tanggal'            => 'tanggal',
                'semester_cuti'      => '@semester_cuti',
                'tanggal_mulai_cuti' => '@tanggal_mulai_cuti',
                'tanggal_selesai_cuti' => '@tanggal_selesai_cuti',
                'alasan_cuti'        => '@alasan_cuti',
                'alamat'             => 'alamat',
                'no_hp'              => 'no_hp',
            ],
        ];
    }

    // ──────────────────────────────────────────────────────────────
    //  Jenis 3: Dispensasi Perkuliahan
    // ──────────────────────────────────────────────────────────────
    private static function dispensasi(): array
    {
        return [
            'label'    => 'Dispensasi Perkuliahan',
            'template' => 'Surat Permohonan Dispensasi Perkuliahan.docx',
            'icon'     => 'fa-calendar-times',
            'color'    => 'purple',

            'fields' => [
                [
                    'name'        => 'nama_kegiatan',
                    'label'       => 'Nama Kegiatan',
                    'type'        => 'text',
                    'placeholder' => 'Contoh: Lomba Debat Hukum Nasional 2026',
                    'required'    => true,
                    'rules'       => 'required|string|max:255',
                ],
                [
                    'name'        => 'penyelenggara',
                    'label'       => 'Penyelenggara Kegiatan',
                    'type'        => 'text',
                    'placeholder' => 'Contoh: Universitas Indonesia',
                    'required'    => true,
                    'rules'       => 'required|string|max:255',
                ],
                [
                    'name'        => 'tanggal_dispensasi',
                    'label'       => 'Tanggal Pelaksanaan',
                    'type'        => 'date',
                    'required'    => true,
                    'rules'       => 'required|date',
                ],
                [
                    'name'        => 'tanggal_selesai_dispensasi',
                    'label'       => 'Tanggal Selesai (jika multi-hari)',
                    'type'        => 'date',
                    'required'    => false,
                    'rules'       => 'nullable|date|after_or_equal:payload_template.tanggal_dispensasi',
                ],
                [
                    'name'        => 'mata_kuliah_ditinggal',
                    'label'       => 'Mata Kuliah yang Ditinggalkan',
                    'type'        => 'text',
                    'placeholder' => 'Contoh: Hukum Perdata, Hukum Pidana',
                    'required'    => true,
                    'rules'       => 'required|string|max:500',
                ],
            ],

            'placeholders' => [
                'nama'                     => 'nama',
                'nim'                      => 'nim',
                'prodi'                    => 'prodi',
                'fakultas'                 => 'fakultas',
                'semester'                 => 'semester',
                'tahun_ajaran'             => 'tahun_ajaran',
                'tanggal'                  => 'tanggal',
                'nama_kegiatan'            => '@nama_kegiatan',
                'penyelenggara'            => '@penyelenggara',
                'tanggal_dispensasi'       => '@tanggal_dispensasi',
                'tanggal_selesai_dispensasi' => '@tanggal_selesai_dispensasi',
                'mata_kuliah_ditinggal'    => '@mata_kuliah_ditinggal',
                'alamat'                   => 'alamat',
                'no_hp'                    => 'no_hp',
            ],
        ];
    }

    // ──────────────────────────────────────────────────────────────
    //  Jenis 4: Izin Penelitian
    // ──────────────────────────────────────────────────────────────
    private static function izinPenelitian(): array
    {
        return [
            'label'    => 'Izin Penelitian',
            'template' => 'Surat Permohonan Izin Penelitian.docx',
            'icon'     => 'fa-search',
            'color'    => 'green',

            'fields' => [
                [
                    'name'        => 'judul_penelitian',
                    'label'       => 'Judul Penelitian',
                    'type'        => 'text',
                    'placeholder' => 'Contoh: Analisis Penegakan Hukum...',
                    'required'    => true,
                    'rules'       => 'required|string|max:500',
                ],
                [
                    'name'        => 'tujuan_penelitian',
                    'label'       => 'Tujuan / Lokasi Penelitian',
                    'type'        => 'text',
                    'placeholder' => 'Contoh: Pengadilan Negeri Jakarta Selatan',
                    'required'    => true,
                    'rules'       => 'required|string|max:255',
                ],
                [
                    'name'        => 'instansi_tujuan',
                    'label'       => 'Instansi Tujuan',
                    'type'        => 'text',
                    'placeholder' => 'Contoh: Dinas Hukum dan HAM Prov. DKI Jakarta',
                    'required'    => true,
                    'rules'       => 'required|string|max:255',
                ],
                [
                    'name'        => 'tanggal_mulai_penelitian',
                    'label'       => 'Tanggal Mulai Penelitian',
                    'type'        => 'date',
                    'required'    => true,
                    'rules'       => 'required|date',
                ],
                [
                    'name'        => 'tanggal_selesai_penelitian',
                    'label'       => 'Tanggal Selesai Penelitian',
                    'type'        => 'date',
                    'required'    => true,
                    'rules'       => 'required|date|after_or_equal:payload_template.tanggal_mulai_penelitian',
                ],
            ],

            'placeholders' => [
                'nama'                      => 'nama',
                'nim'                       => 'nim',
                'prodi'                     => 'prodi',
                'fakultas'                  => 'fakultas',
                'semester'                  => 'semester',
                'tahun_ajaran'              => 'tahun_ajaran',
                'tanggal'                   => 'tanggal',
                'judul_penelitian'          => '@judul_penelitian',
                'tujuan_penelitian'         => '@tujuan_penelitian',
                'instansi_tujuan'           => '@instansi_tujuan',
                'tanggal_mulai_penelitian'  => '@tanggal_mulai_penelitian',
                'tanggal_selesai_penelitian'=> '@tanggal_selesai_penelitian',
                'alamat'                    => 'alamat',
                'no_hp'                     => 'no_hp',
            ],
        ];
    }
}
