<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Anda Telah Terbit</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px; color: #334155;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <h2 style="color: #1e3a8a; margin-top: 0;">Surat Permohonan Anda Telah Terbit</h2>
        <p>Halo {{ $letterRequest->user->name }},</p>
        <p>Permohonan <strong>{{ $letterRequest->template->name ?? 'Surat Resmi' }}</strong> yang Anda ajukan telah diverifikasi dan disetujui secara resmi oleh Pemerintah Desa Mengeruda.</p>
        <p>Surat digital Anda dilengkapi dengan tanda tangan elektronik berupa <strong>QR Code</strong> yang sah dan dapat divalidasi keasliannya.</p>
        <div style="margin: 25px 0;">
            <a href="http://e-surat.mengeruda.id/dashboard" style="background-color: #1d4ed8; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; display: inline-block;">Unduh Surat PDF</a>
        </div>
        <p style="font-size: 14px; color: #475569;">Atau Anda dapat langsung mengunduh melalui tautan API: <br /><a href="{{ $downloadUrl }}">{{ $downloadUrl }}</a></p>
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;" />
        <p style="font-size: 12px; color: #64748b;">Sistem Pelayanan Surat Menyurat Digital - Desa Mengeruda</p>
    </div>
</body>
</html>
