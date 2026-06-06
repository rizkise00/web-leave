<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pengajuan Cuti Baru</title>
    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; }
        body { margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; }
        a { color: #30318B; }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f3f4f6;">
    <tr>
        <td align="center" style="padding: 32px 16px;">

            <!-- Card -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                <!-- Header -->
                <tr>
                    <td style="background: linear-gradient(135deg, #30318B 0%, #4a1a8a 100%); padding: 32px 36px;">
                        <!-- Logo row -->
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="vertical-align:middle;">
                                        <img src="{{ asset('assets/logo.png') }}" alt="Web Cuti" width="40" height="40" style="display:block; width:40px; height:40px; object-fit:contain;">
                                </td>
                                <td style="padding-left:12px; vertical-align:middle;">
                                    <span style="color:#ffffff; font-size:16px; font-weight:800; letter-spacing:0.5px;">Web Cuti</span>
                                </td>
                            </tr>
                        </table>
                        <!-- Title -->
                        <p style="margin:20px 0 4px; color:#ffffff; font-size:22px; font-weight:800; line-height:1.3;">Pengajuan Cuti Baru</p>
                        <p style="margin:0; color:rgba(255,255,255,0.75); font-size:13px;">Ada pengajuan cuti yang memerlukan persetujuan Anda</p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding: 32px 36px;">

                        <!-- Greeting -->
                        <p style="margin:0 0 20px; font-size:15px; color:#374151; line-height:1.7;">
                            Halo <strong>Manajer</strong>,<br>
                            Karyawan <strong style="color:#30318B;">{{ $cuti->user->name }}</strong> baru saja mengajukan permohonan cuti.
                            Silakan tinjau dan berikan keputusan Anda.
                        </p>

                        <!-- Badge -->
                        <p style="margin:0 0 24px;">
                            <span style="display:inline-block; background:#fef3c7; color:#92400e; border:1px solid #fde68a; padding:5px 14px; border-radius:99px; font-size:12px; font-weight:700;">
                                ⏳ Menunggu Persetujuan
                            </span>
                        </p>

                        <!-- Detail table -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                            style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; margin-bottom:24px; overflow:hidden;">

                            <!-- Row: Nama -->
                            <tr style="border-bottom:1px solid #f3f4f6;">
                                <td width="45%" style="padding:13px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.6px; vertical-align:top; border-bottom:1px solid #f3f4f6;">
                                    Nama Karyawan
                                </td>
                                <td style="padding:13px 20px; font-size:14px; font-weight:600; color:#111827; vertical-align:top; border-bottom:1px solid #f3f4f6;">
                                    {{ $cuti->user->name }}
                                </td>
                            </tr>

                            <!-- Row: Email -->
                            <tr>
                                <td width="45%" style="padding:13px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.6px; vertical-align:top; border-bottom:1px solid #f3f4f6;">
                                    Email
                                </td>
                                <td style="padding:13px 20px; font-size:14px; color:#374151; vertical-align:top; border-bottom:1px solid #f3f4f6;">
                                    {{ $cuti->user->email }}
                                </td>
                            </tr>

                            <!-- Row: Periode -->
                            <tr>
                                <td width="45%" style="padding:13px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.6px; vertical-align:top; border-bottom:1px solid #f3f4f6;">
                                    Periode Cuti
                                </td>
                                <td style="padding:13px 20px; font-size:14px; color:#374151; vertical-align:top; border-bottom:1px solid #f3f4f6;">
                                    {{ $cuti->tanggal_mulai->translatedFormat('d F Y') }}
                                    @if($cuti->tanggal_mulai->ne($cuti->tanggal_selesai))
                                        &nbsp;–&nbsp;{{ $cuti->tanggal_selesai->translatedFormat('d F Y') }}
                                    @endif
                                </td>
                            </tr>

                            <!-- Row: Durasi -->
                            <tr>
                                <td width="45%" style="padding:13px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.6px; vertical-align:top; border-bottom:1px solid #f3f4f6;">
                                    Durasi
                                </td>
                                <td style="padding:13px 20px; vertical-align:top; border-bottom:1px solid #f3f4f6;">
                                    <span style="display:inline-block; background:#ede9fe; color:#5b21b6; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700;">
                                        {{ $cuti->jumlah_hari }} hari
                                    </span>
                                </td>
                            </tr>

                            <!-- Row: Diajukan -->
                            <tr>
                                <td width="45%" style="padding:13px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.6px; vertical-align:top;">
                                    Diajukan Pada
                                </td>
                                <td style="padding:13px 20px; font-size:14px; color:#374151; vertical-align:top;">
                                    {{ $cuti->created_at->translatedFormat('d F Y, H:i') }}
                                </td>
                            </tr>

                        </table>

                        <!-- Alasan -->
                        <p style="margin:0 0 8px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.6px;">
                            Alasan Pengajuan
                        </p>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
                            <tr>
                                <td style="padding:14px 16px 14px 20px; background:#f9fafb; border-left:4px solid #30318B; border-radius:0 8px 8px 0; font-size:14px; color:#374151; line-height:1.7;">
                                    {{ $cuti->alasan }}
                                </td>
                            </tr>
                        </table>

                        <!-- CTA Button -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:8px;">
                            <tr>
                                <td align="center">
                                    <a href="{{ url('/manajer/cuti') }}"
                                        style="display:inline-block; background:linear-gradient(135deg,#30318B,#4a1a8a); color:#ffffff; text-decoration:none; padding:14px 32px; border-radius:10px; font-size:14px; font-weight:700; letter-spacing:0.3px;">
                                        Buka Halaman Kelola Cuti &rarr;
                                    </a>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding:20px 36px; background:#f9fafb; border-top:1px solid #f3f4f6; text-align:center;">
                        <p style="margin:0; font-size:12px; color:#9ca3af; line-height:1.7;">
                            Email ini dikirim otomatis oleh sistem <strong>Web Cuti</strong>.<br>
                            Jangan balas email ini &mdash; akses sistem di
                            <a href="{{ url('/') }}" style="color:#30318B; text-decoration:none; font-weight:600;">{{ parse_url(url('/'), PHP_URL_HOST) }}</a>
                        </p>
                    </td>
                </tr>

            </table>
            <!-- /Card -->

        </td>
    </tr>
</table>

</body>
</html>
