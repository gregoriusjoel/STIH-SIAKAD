<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\IOFactory;

class CreateLetterTemplates extends Command
{
    protected $signature   = 'letters:create-templates';
    protected $description = 'Create proper .docx templates with ${placeholder} markers for TemplateProcessor';

    private array $font     = ['name' => 'Times New Roman', 'size' => 12];
    private array $fontBold = ['name' => 'Times New Roman', 'size' => 12, 'bold' => true];

    public function handle(): int
    {
        $dir = base_path('docs');

        if (!is_dir($dir)) {
            $this->error("Directory not found: $dir");
            return self::FAILURE;
        }

        $this->makeCuti($dir);
        $this->makeDispensasi($dir);
        $this->makeIzin($dir);
        $this->makeAktif($dir);

        $this->info('All 4 templates created successfully.');
        return self::SUCCESS;
    }

    // ─── CUTI AKADEMIK ──────────────────────────────────────────────────────────

    private function makeCuti(string $dir): void
    {
        $w  = $this->newDoc();
        $sc = $w->addSection($this->sec());

        $this->h($sc, 'SURAT PERMOHONAN CUTI AKADEMIK');
        $sc->addTextBreak(1);

        foreach (['Kepada Yth.', 'Ketua Program Studi STIH Adhyaksa', 'di', 'Tempat'] as $line) {
            $sc->addText($line, $this->font);
        }

        $sc->addTextBreak(1);
        $sc->addText('Dengan hormat,', $this->font);
        $sc->addText(
            'Yang bertanda tangan di bawah ini, saya mahasiswa Program Studi Hukum STIH Adhyaksa:',
            $this->font
        );
        $sc->addTextBreak(1);

        $t = $sc->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $this->row($t, 'Nama',         '${nama}');
        $this->row($t, 'NIM',          '${nim}');
        $this->row($t, 'Program Studi','${prodi}');
        $this->row($t, 'Alamat',       '${alamat}');
        $this->row($t, 'No. Telepon',  '${no_hp}');

        $sc->addTextBreak(1);

        $r = $sc->addTextRun();
        $r->addText('Mengajukan permohonan cuti akademik selama ', $this->font);
        $r->addText('${semester_cuti}', $this->font);
        $r->addText(' semester pada Semester / Tahun Akademik ', $this->font);
        $r->addText('${tahun_ajaran}', $this->font);
        $r->addText(' dengan alasan ', $this->font);
        $r->addText('${alasan_cuti}', $this->font);
        $r->addText('.', $this->font);

        $sc->addTextBreak(1);
        $sc->addText('Sebagai bahan pertimbangan, saya lampirkan:', $this->font);
        foreach (['Fotokopi KTM,', 'KHS Semester yang bersangkutan, dan*', 'Slip pembayaran SPP Semester yang bersangkutan*'] as $item) {
            $sc->addListItem($item, 0, $this->font);
        }

        $sc->addTextBreak(1);
        $sc->addText(
            'Demikian surat permohonan ini saya sampaikan. Atas perhatian dan kebijaksanaan Ibu, saya mengucapkan terima kasih.',
            $this->font
        );
        $sc->addTextBreak(2);

        $this->sigDouble($sc);
        $this->save($w, $dir, 'Surat Permohonan Cuti Akademik.docx');
        $this->info('  [OK] Surat Permohonan Cuti Akademik.docx');
    }

    // ─── DISPENSASI PERKULIAHAN ──────────────────────────────────────────────────

    private function makeDispensasi(string $dir): void
    {
        $w  = $this->newDoc();
        $sc = $w->addSection($this->sec());

        $this->h($sc, 'SURAT PERMOHONAN DISPENSASI PERKULIAHAN');
        $sc->addTextBreak(1);

        foreach (['Kepada Yth.', 'Kepala Bagian Akademik', 'STIH Adhyaksa', 'di Tempat'] as $line) {
            $sc->addText($line, $this->font);
        }

        $sc->addTextBreak(1);
        $sc->addText('Dengan hormat,', $this->font);
        $sc->addText('Saya yang bertanda tangan di bawah ini:', $this->font);
        $sc->addTextBreak(1);

        $t = $sc->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $this->row($t, 'Nama',          '${nama}');
        $this->row($t, 'NIM',           '${nim}');
        $this->row($t, 'Program Studi', '${prodi}');
        $this->row($t, 'Semester',      '${semester}');
        $this->row($t, 'No. Telepon',   '${no_hp}');

        $sc->addTextBreak(1);
        $sc->addText('Dengan ini mengajukan permohonan dispensasi tidak hadir pada:', $this->font);
        $sc->addTextBreak(1);

        $t2 = $sc->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $this->row($t2, 'Hari, Tanggal',          '${tanggal_dispensasi}');
        $this->row($t2, 'Nama Kegiatan',           '${nama_kegiatan}');
        $this->row($t2, 'Penyelenggara',           '${penyelenggara}');
        $this->row($t2, 'Mata Kuliah Ditinggalkan','${mata_kuliah_ditinggal}');

        $sc->addTextBreak(1);
        $sc->addText(
            'Demikian surat permohonan ini saya sampaikan. Atas perhatian dan kebijaksanaan Bapak/Ibu, saya mengucapkan terima kasih.',
            $this->font
        );
        $sc->addTextBreak(2);

        $this->sigSingle($sc);
        $this->save($w, $dir, 'Surat Permohonan Dispensasi Perkuliahan.docx');
        $this->info('  [OK] Surat Permohonan Dispensasi Perkuliahan.docx');
    }

    // ─── IZIN PENELITIAN ─────────────────────────────────────────────────────────

    private function makeIzin(string $dir): void
    {
        $w  = $this->newDoc();
        $sc = $w->addSection($this->sec());

        $this->h($sc, 'SURAT PERMOHONAN IZIN PENELITIAN');
        $sc->addTextBreak(1);

        foreach (['Kepada Yth.', 'Kepala Bagian Akademik', 'STIH Adhyaksa', 'di Tempat'] as $line) {
            $sc->addText($line, $this->font);
        }

        $sc->addTextBreak(1);
        $sc->addText('Dengan hormat,', $this->font);
        $sc->addText('Saya yang bertanda tangan di bawah ini:', $this->font);
        $sc->addTextBreak(1);

        $t = $sc->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $this->row($t, 'Nama',          '${nama}');
        $this->row($t, 'NIM',           '${nim}');
        $this->row($t, 'Program Studi', '${prodi}');
        $this->row($t, 'Semester',      '${semester}');
        $this->row($t, 'No. Telepon',   '${no_hp}');

        $sc->addTextBreak(1);
        $sc->addText(
            'Dalam rangka penyusunan tugas akhir/skripsi, saya mengajukan permohonan izin penelitian dengan rincian:',
            $this->font
        );
        $sc->addTextBreak(1);

        $t2 = $sc->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $this->row($t2, 'Judul Penelitian',   '${judul_penelitian}');
        $this->row($t2, 'Lokasi / Tujuan',    '${tujuan_penelitian}');
        $this->row($t2, 'Instansi Tujuan',    '${instansi_tujuan}');
        $this->row($t2, 'Tanggal Mulai',      '${tanggal_mulai_penelitian}');
        $this->row($t2, 'Tanggal Selesai',    '${tanggal_selesai_penelitian}');

        $sc->addTextBreak(1);
        $sc->addText(
            'Demikian surat permohonan ini saya sampaikan. Atas perhatian dan kebijaksanaan Bapak/Ibu, saya mengucapkan terima kasih.',
            $this->font
        );
        $sc->addTextBreak(2);

        $this->sigSingle($sc);
        $this->save($w, $dir, 'Surat Permohonan Izin Penelitian.docx');
        $this->info('  [OK] Surat Permohonan Izin Penelitian.docx');
    }

    // ─── SURAT KETERANGAN AKTIF ──────────────────────────────────────────────────

    private function makeAktif(string $dir): void
    {
        $w  = $this->newDoc();
        $sc = $w->addSection($this->sec());

        $this->h($sc, 'SURAT PERMOHONAN PENERBITAN SURAT KETERANGAN AKTIF MAHASISWA');
        $sc->addTextBreak(1);

        foreach (['Kepada Yth.', 'Kepala Bagian Akademik', 'STIH Adhyaksa', 'di Tempat'] as $line) {
            $sc->addText($line, $this->font);
        }

        $sc->addTextBreak(1);
        $sc->addText('Dengan hormat,', $this->font);
        $sc->addText('Saya yang bertanda tangan di bawah ini:', $this->font);
        $sc->addTextBreak(1);

        $t = $sc->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $this->row($t, 'Nama',          '${nama}');
        $this->row($t, 'NIM',           '${nim}');
        $this->row($t, 'Program Studi', '${prodi}');
        $this->row($t, 'Semester',      '${semester}');
        $this->row($t, 'Alamat',        '${alamat}');
        $this->row($t, 'No. Telepon',   '${no_hp}');

        $sc->addTextBreak(1);

        $r = $sc->addTextRun();
        $r->addText(
            'Dengan ini mengajukan permohonan kepada Bagian Akademik untuk dapat diterbitkan Surat Keterangan Aktif Mahasiswa, untuk keperluan ',
            $this->font
        );
        $r->addText('${tujuan}', $this->font);
        $r->addText('.', $this->font);

        $sc->addTextBreak(1);
        $sc->addText(
            'Demikian surat permohonan ini saya sampaikan. Atas perhatian dan kebijaksanaan Bapak/Ibu, saya mengucapkan terima kasih.',
            $this->font
        );
        $sc->addTextBreak(2);

        $this->sigSingle($sc);
        $this->save($w, $dir, 'Surat Permohonan Penerbitan Surat Keterangan Aktif Mahasiswa.docx');
        $this->info('  [OK] Surat Permohonan Penerbitan Surat Keterangan Aktif Mahasiswa.docx');
    }

    // ─── HELPERS ─────────────────────────────────────────────────────────────────

    private function newDoc(): PhpWord
    {
        $w = new PhpWord();
        $w->setDefaultFontName('Times New Roman');
        $w->setDefaultFontSize(12);
        return $w;
    }

    private function sec(): array
    {
        return [
            'marginTop'    => Converter::cmToTwip(2.5),
            'marginBottom' => Converter::cmToTwip(2.5),
            'marginLeft'   => Converter::cmToTwip(3),
            'marginRight'  => Converter::cmToTwip(2),
        ];
    }

    private function h($sc, string $text): void
    {
        $sc->addText($text, $this->fontBold, ['alignment' => Jc::CENTER]);
    }

    private function row($table, string $label, string $value): void
    {
        $row = $table->addRow();
        $row->addCell(3000)->addText($label, $this->font);
        $row->addCell(300)->addText(':', $this->font);
        $row->addCell(7000)->addText($value, $this->font);
    }

    private function sigSingle($sc): void
    {
        $t   = $sc->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $row = $t->addRow();
        $row->addCell(6000);            // left spacer
        $c = $row->addCell(4000);
        $c->addText('Jakarta, ${tanggal}', $this->font);
        $c->addText('Hormat saya,', $this->font);
        $c->addTextBreak(3);
        $c->addText('${nama}', $this->font);
        $c->addText('NIM. ${nim}', $this->font);
    }

    private function sigDouble($sc): void
    {
        $t   = $sc->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $row = $t->addRow();

        $cL = $row->addCell(5000);
        $cL->addText('Mengetahui', $this->font);
        $cL->addText('Orang Tua / Wali Mahasiswa,', $this->font);
        $cL->addTextBreak(3);
        $cL->addText('(................................)', $this->font);

        $cR = $row->addCell(5000);
        $cR->addText('Jakarta, ${tanggal}', $this->font);
        $cR->addText('Hormat saya,', $this->font);
        $cR->addTextBreak(3);
        $cR->addText('${nama}', $this->font);
        $cR->addText('NIM. ${nim}', $this->font);
    }

    private function save(PhpWord $w, string $dir, string $filename): void
    {
        $path = $dir . '/' . $filename;
        if (file_exists($path) && !file_exists($path . '.bak')) {
            copy($path, $path . '.bak');
        }
        IOFactory::createWriter($w, 'Word2007')->save($path);
    }
}
