<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pendaftaran Akun E-Surat Baru</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px; color: #334155;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <h2 style="color: #1e3a8a; margin-top: 0;">Pendaftaran Akun Warga Baru</h2>
        <p>Halo Admin,</p>
        <p>Terdapat pendaftaran akun baru pada sistem E-Surat Desa Mengeruda yang menunggu verifikasi Anda:</p>
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 8px 0; font-weight: bold; width: 120px;">NIK</td>
                <td style="padding: 8px 0;">: {{ $user->nik }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Nama</td>
                <td style="padding: 8px 0;">: {{ $user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Email</td>
                <td style="padding: 8px 0;">: {{ $user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">No. HP</td>
                <td style="padding: 8px 0;">: {{ $user->phone ?? '-' }}</td>
            </tr>
        </table>
        <p>Silakan masuk ke Panel Admin E-Surat untuk memeriksa dokumen KTP dan melakukan verifikasi akun.</p>
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;" />
        <p style="font-size: 12px; color: #64748b;">Sistem Pelayanan Surat Menyurat Digital - Desa Mengeruda</p>
    </div>
</body>
</html>
