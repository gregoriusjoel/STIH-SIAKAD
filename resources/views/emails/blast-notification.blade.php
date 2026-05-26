<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6fc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">

    @php
        // Cek apakah email berisi informasi jadwal wisuda
        $isGraduation = false;
        $batch = '';
        $tanggal = '';
        $waktu = '';
        $lokasi = '';

        if (preg_match('/Batch:\s*(.*)/i', $messageBody, $matches)) {
            $isGraduation = true;
            $batch = trim($matches[1]);
        }
        if (preg_match('/Tanggal:\s*(.*)/i', $messageBody, $matches)) {
            $tanggal = trim($matches[1]);
        }
        if (preg_match('/Waktu:\s*(.*)/i', $messageBody, $matches)) {
            $waktu = trim($matches[1]);
        }
        if (preg_match('/Lokasi:\s*(.*)/i', $messageBody, $matches)) {
            $lokasi = trim($matches[1]);
        }

        // Tentukan nama penerima dari greeting (contoh: "Yth. Jojo" -> "Jojo")
        $recipientName = 'Mahasiswa';
        if (preg_match('/Yth\.\s*(.*)/i', $greeting, $matches)) {
            $recipientName = trim($matches[1]);
        }
    @endphp

    <div style="background-color: #f4f6fc; padding: 40px 20px;">
        <!-- Card Container Utama -->
        <div style="max-width: 650px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); overflow: hidden; padding: 40px;">
            
            <!-- Header Brand -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px;">
                <tr>
                    <td align="left" valign="middle">
                        <span style="font-family: Georgia, serif; font-size: 20px; font-weight: bold; color: #0f172a; letter-spacing: -0.5px;">Universitas Adhyaksa</span>
                    </td>
                    <td align="right" valign="middle">
                        <div style="width: 36px; height: 36px; background-color: #8B1538; border-radius: 50%; text-align: center; line-height: 36px; display: inline-block;">
                            <span style="font-family: Georgia, serif; font-size: 12px; font-weight: bold; color: #dfab37; letter-spacing: 0.5px;">SA</span>
                        </div>
                    </td>
                </tr>
            </table>

            @if($isGraduation)
                <!-- ================= LAYOUT UNDANGAN WISUDA PREMIUM ================= -->
                <!-- Judul Utama -->
                <h1 style="font-family: Georgia, serif; font-size: 36px; font-weight: bold; color: #0f172a; margin: 0 0 6px 0; line-height: 1.2;">Jadwal Wisuda Anda</h1>
                <p style="font-size: 14px; font-style: italic; color: #64748b; margin: 0 0 35px 0;">Sebuah perayaan atas dedikasi dan pencapaian akademik Anda.</p>
 
                <!-- Greeting & Pembuka -->
                <p style="font-family: Georgia, serif; font-size: 18px; font-weight: bold; color: #0f172a; margin: 0 0 10px 0;">Yth. {{ $recipientName }},</p>
                <p style="font-size: 14px; color: #334155; line-height: 1.6; margin: 0 0 30px 0;">Berikut adalah rincian resmi untuk upacara wisuda Anda. Kami berharap dapat merayakan momen bersejarah ini bersama Anda.</p>
 
                <!-- Box Grid Informasi Wisuda -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #eff6ff; border-radius: 8px; padding: 25px; margin-bottom: 30px;">
                    <tr>
                        <!-- Batch Wisuda -->
                        <td width="50%" align="left" valign="top" style="padding-bottom: 25px; padding-right: 15px;">
                            <div style="border-left: 3px solid #b45309; padding-left: 12px;">
                                <span style="font-size: 10px; font-weight: bold; color: #b45309; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 6px;">Batch Wisuda</span>
                                <strong style="font-size: 18px; color: #0f172a; display: block; font-family: Georgia, serif; font-weight: bold;">{{ $batch }}</strong>
                            </div>
                        </td>
                        <!-- Lokasi Acara -->
                        <td width="50%" align="left" valign="top" style="padding-bottom: 25px;">
                            <div style="border-left: 3px solid #b45309; padding-left: 12px;">
                                <span style="font-size: 10px; font-weight: bold; color: #b45309; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 6px;">Lokasi Acara</span>
                                <strong style="font-size: 18px; color: #0f172a; display: block; font-family: Georgia, serif; font-weight: bold;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="#b45309" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                    </svg><span style="vertical-align: middle;">{{ $lokasi }}</span>
                                </strong>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <!-- Waktu & Tanggal -->
                        <td colspan="2" valign="middle">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 1px solid #dbeafe; padding-top: 20px;">
                                <tr>
                                    <td align="left" valign="top">
                                        <div style="border-left: 3px solid #b45309; padding-left: 12px;">
                                            <span style="font-size: 10px; font-weight: bold; color: #b45309; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 6px;">Waktu & Tanggal</span>
                                            <strong style="font-size: 18px; color: #0f172a; display: block; font-family: Georgia, serif; font-weight: bold;">{{ $tanggal }}</strong>
                                            <span style="font-size: 13px; color: #64748b; display: block; margin-top: 4px;">{{ $waktu }}</span>
                                        </div>
                                    </td>
                                    <td align="right" valign="middle" style="padding-left: 15px;">
                                        @if($actionUrl && $actionText)
                                        <a href="{{ $actionUrl }}" style="background-color: #000000; color: #ffffff; padding: 14px 24px; text-decoration: none; font-size: 11px; font-weight: bold; border-radius: 4px; display: inline-block; text-transform: uppercase; letter-spacing: 1px;">{{ $actionText }}</a>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Hero Image Auditorium -->
                <div style="margin-bottom: 35px; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);">
                    <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=1200&auto=format&fit=crop" alt="Graduation Auditorium" style="width: 100%; height: auto; display: block; max-height: 280px; object-fit: cover;" />
                </div>

                <!-- Informasi Penting -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 25px; margin-bottom: 35px; background-color: #ffffff;">
                    <tr>
                        <td valign="top" style="padding-right: 15px; width: 24px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="#b45309" style="display: block;">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                            </svg>
                        </td>
                        <td valign="top">
                            <h3 style="font-size: 16px; font-weight: bold; color: #0f172a; margin: 0 0 12px 0;">Informasi Penting:</h3>
                            <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #475569; line-height: 1.8;">
                                <li style="margin-bottom: 8px;">Silakan login ke portal SIAKAD untuk mengunduh kartu undangan wisuda, panduan pelaksanaan, serta denah lokasi acara.</li>
                                <li>Jika terdapat kesalahan data pada jadwal wisuda di atas, segera laporkan ke Biro Administrasi Akademik (BAA).</li>
                            </ul>
                        </td>
                    </tr>
                </table>
            @else
                <!-- ================= LAYOUT EMAIL UMUM PREMIUM ================= -->
                <!-- Judul Utama -->
                <h1 style="font-family: Georgia, serif; font-size: 28px; font-weight: bold; color: #0f172a; margin: 0 0 20px 0; line-height: 1.3;">{{ $subject }}</h1>

                <!-- Greeting & Pembuka -->
                <p style="font-family: Georgia, serif; font-size: 16px; font-weight: bold; color: #0f172a; margin: 0 0 15px 0;">Yth. {{ $recipientName }},</p>
                <div style="font-size: 14px; color: #334155; line-height: 1.7; margin-bottom: 30px;">
                    {!! nl2br(e($messageBody)) !!}
                </div>

                @if($actionUrl && $actionText)
                <div style="margin: 30px 0; text-align: left;">
                    <a href="{{ $actionUrl }}" style="background-color: #000000; color: #ffffff; padding: 14px 28px; text-decoration: none; font-size: 12px; font-weight: bold; border-radius: 4px; display: inline-block; text-transform: uppercase; letter-spacing: 1px;">{{ $actionText }}</a>
                </div>
                @endif
            @endif

            <!-- Penutup & Tanda Tangan -->
            <div style="border-top: 1px solid #f1f5f9; padding-top: 25px; margin-top: 20px;">
                <p style="font-size: 14px; color: #475569; margin: 0 0 15px 0;">Terima kasih atas perhatian Anda.</p>
                <p style="font-size: 14px; font-style: italic; color: #475569; margin: 0 0 4px 0;">Salam hangat,</p>
                <p style="font-size: 14px; font-weight: bold; color: #0f172a; margin: 0;">Panitia Wisuda Universitas Adhyaksa</p>
            </div>

        </div>

        <!-- Footer Bawah Halaman -->
        <div style="max-width: 650px; margin: 25px auto 0 auto; padding: 0 10px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td align="left" style="font-size: 11px; color: #94a3b8; font-family: -apple-system, BlinkMacSystemFont, sans-serif;">
                        &copy; {{ date('Y') }} Universitas Adhyaksa. All rights reserved.
                    </td>
                    <td align="right" style="font-size: 11px; color: #94a3b8; font-family: -apple-system, BlinkMacSystemFont, sans-serif;">
                        <a href="#" style="color: #94a3b8; text-decoration: none; margin-right: 15px;">Privacy Policy</a>
                        <a href="#" style="color: #94a3b8; text-decoration: none;">Contact Support</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
