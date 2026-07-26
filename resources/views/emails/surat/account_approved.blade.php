<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Akun Anda Disetujui</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px; color: #334155;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <h2 style="color: #1e3a8a; margin-top: 0;">Selamat, Akun Anda Telah Disetujui!</h2>
        <p>Halo {{ $user->name }},</p>
        <p>Kabar baik! Akun pendaftaran E-Surat Desa Mengeruda Anda dengan NIK <strong>{{ $user->nik }}</strong> telah selesai diverifikasi dan disetujui oleh Administrator Desa.</p>
        <p>Sekarang Anda dapat masuk ke dasbor warga untuk melakukan pengajuan permohonan surat secara online kapan saja.</p>
        <div style="margin: 25px 0;">
            <a href="http://e-surat.mengeruda.id" style="background-color: #1d4ed8; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; display: inline-block;">Masuk ke E-Surat</a>
        </div>
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;" />
        <p style="font-size: 12px; color: #64748b;">Sistem Pelayanan Surat Menyurat Digital - Desa Mengeruda</p>
    </div>
</body>
</html>
