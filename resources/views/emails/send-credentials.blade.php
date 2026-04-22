<x-mail::message>
# {{ $custom_greeting ?? 'Selamat Datang di SIAKAD STIH' }}

Halo **{{ $mahasiswa->user->name }}** ({{ $mahasiswa->nim }}),

@if(!empty($custom_message))
{!! nl2br(e($custom_message)) !!}

<x-mail::panel>
**Email Kampus:** {{ $email_kampus }}<br>
**Password:** {{ $password }}
</x-mail::panel>
@else
Akun SIAKAD Anda telah dibuat. Berikut adalah kredensial login Anda:

<x-mail::panel>
**Email Kampus:** {{ $email_kampus }}<br>
**Password:** {{ $password }}
</x-mail::panel>
@endif

<x-mail::button :url="$login_url" color="primary">
Login ke SIAKAD
</x-mail::button>

⚠️ **PENTING:** Simpan password ini dengan aman. Anda bisa mengubah password setelah login pertama kali.

---

### Informasi Akun Anda:

- **NIM:** {{ $mahasiswa->nim }}
- **Nama:** {{ $mahasiswa->user->name }}
- **Program Studi:** {{ $mahasiswa->prodi->nama_prodi ?? $mahasiswa->prodi }}
- **Angkatan:** {{ $mahasiswa->angkatan }}

Jika Anda mengalami kesulitan, silakan hubungi bagian akademik STIH Adhyaksa.

Terima kasih,<br>
**SIAKAD STIH Adhyaksa**
</x-mail::message>
