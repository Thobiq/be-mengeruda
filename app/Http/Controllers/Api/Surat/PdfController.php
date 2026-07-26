<?php

namespace App\Http\Controllers\Api\Surat;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfController extends Controller
{
    /**
     * Helper Static Method untuk merender dan menyimpan file PDF ber-QR Code
     */
    public static function generateLetterPdf(LetterRequest $letterRequest): string
    {
        $letterRequest->load(['user', 'template']);

        // URL validasi tanda tangan elektronik
        $validationUrl = "http://e-surat.mengeruda.id/validasi?token=" . $letterRequest->token;

        // Generate QR Code format SVG base64 agar kompatibel dengan DOMPDF
        $qrSvg = QrCode::format('svg')->size(110)->generate($validationUrl);
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        // Render PDF dari pandangan (view) Blade
        $pdf = Pdf::loadView('pdf.surat', [
            'letterRequest' => $letterRequest,
            'qrCodeBase64' => $qrCodeBase64,
        ])->setPaper('a4', 'portrait');

        $fileName = 'surat_' . $letterRequest->id . '_' . time() . '.pdf';
        $filePath = 'surat/' . $fileName;

        // Simpan ke storage (disk public atau private)
        Storage::disk('local')->put($filePath, $pdf->output());

        return $filePath;
    }

    /**
     * Download Berkas PDF Surat Resmi (Hanya Admin atau Pemohon)
     */
    public function downloadPdf(Request $request, $id)
    {
        $letterRequest = LetterRequest::with(['user', 'template'])->findOrFail($id);
        $user = $request->user();

        // Cek hak akses: hanya admin_surat atau pemohon yang sah
        $isAdmin = $user->isAdminSurat();
        $isOwner = ($letterRequest->user_id === $user->id);

        if (!$isAdmin && !$isOwner) {
            return response()->json(['message' => 'Anda tidak berhak mengakses dokumen ini.'], 403);
        }

        if ($letterRequest->status !== 'approved' || !$letterRequest->pdf_path) {
            return response()->json(['message' => 'Surat belum diterbitkan atau belum disetujui.'], 404);
        }

        if (!Storage::disk('local')->exists($letterRequest->pdf_path)) {
            // Jika file hilang, generate ulang
            $pdfPath = self::generateLetterPdf($letterRequest);
            $letterRequest->pdf_path = $pdfPath;
            $letterRequest->save();
        }

        return Storage::disk('local')->download(
            $letterRequest->pdf_path, 
            'Surat_' . str_replace(' ', '_', $letterRequest->template->name ?? 'Resmi') . '_' . $letterRequest->id . '.pdf'
        );
    }

    /**
     * Publik endpoint untuk validasi keaslian surat berdasarkan QR Token
     */
    public function validateToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $letterRequest = LetterRequest::with(['user', 'template'])
            ->where('token', $request->token)
            ->first();

        if (!$letterRequest || $letterRequest->status !== 'approved') {
            return response()->json([
                'status' => 'error',
                'valid' => false,
                'message' => 'Dokumen tidak ditemukan atau belum disetujui secara resmi.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'valid' => true,
            'message' => 'Dokumen sah dan terverifikasi secara resmi oleh Pemerintah Desa Mengeruda.',
            'data' => [
                'nomor_surat' => '140 / ES / MGR / ' . $letterRequest->created_at->format('m') . ' / ' . $letterRequest->created_at->format('Y'),
                'jenis_surat' => $letterRequest->template->name,
                'nama_pemohon' => $letterRequest->user->name,
                'nik_pemohon' => substr($letterRequest->user->nik, 0, 10) . '******', // Masqued untuk privasi
                'tanggal_terbit' => $letterRequest->updated_at->format('d F Y H:i:s'),
                'status' => 'TERVERIFIKASI / SAH',
            ]
        ]);
    }
}
