<?php

namespace App\Models;

class SurveyQuestion
{
    /**
     * Return an ordered list of survey questions.
     * Each item is [ 'key' => 'q1', 'text' => 'Question text' ]
     */
    public static function all(): array
    {
        return [
            ['key' => 'q1', 'text' => 'Kualitas pelayanan yang diberikan petugas PMB'],
            ['key' => 'q2', 'text' => 'Kejelasan informasi yang diberikan petugas PMB terkait penerimaan mahasiswa baru'],
            ['key' => 'q3', 'text' => 'Tanggungjawab petugas PMB dalam memberikan pelayanan'],
            ['key' => 'q4', 'text' => 'Keramahan petugas PMB dalam memberikan pelayanan kepada calon Mahasiswa'],
            ['key' => 'q5', 'text' => 'Kemudahan akses pendaftaran PMB'],
            ['key' => 'q6', 'text' => 'Kemudahan akses ujian'],
            ['key' => 'q7', 'text' => 'Kemudahan mengakses hasil kelulusan'],
        ];
    }
}
