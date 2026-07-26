<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengajuan Surat Baru</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px; color: #334155;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <h2 style="color: #1e3a8a; margin-top: 0;">Pengajuan Permohonan Surat Baru</h2>
        <p>Halo Admin,</p>
        <p>Terdapat pengajuan permohonan surat baru dari warga yang memerlukan tinjauan Anda:</p>
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 8px 0; font-weight: bold; width: 140px;">Jenis Surat</td>
                <td style="padding: 8px 0;">: {{ $letterRequest->template->name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Pemohon</td>
                <td style="padding: 8px 0;">: {{ $letterRequest->user->name ?? '-' }} (NIK: {{ $letterRequest->user->nik ?? '-' }})</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Tanggal Pengajuan</td>
                <td style="padding: 8px 0;">: {{ $letterRequest->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
        <p>Silakan masuk ke Panel Admin E-Surat untuk memverifikasi data yang dilampirkan dan menyetujui surat.</p>
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;" />
        <p style="font-size: 12px; color: #64748b;">Sistem Pelayanan Surat Menyurat Digital - Desa Mengeruda</p>
    </div>
</body>
</html>
