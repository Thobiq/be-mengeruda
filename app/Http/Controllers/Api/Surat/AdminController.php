<?php

namespace App\Http\Controllers\Api\Surat;

use App\Http\Controllers\Controller;
use App\Mail\Surat\AccountApprovedMail;
use App\Mail\Surat\LetterReadyMail;
use App\Models\LetterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Daftar warga yang menunggu verifikasi (is_approved = false)
     */
    public function getPendingUsers()
    {
        $users = User::where('is_approved', false)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'warga');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    /**
     * Daftar semua warga
     */
    public function getAllUsers()
    {
        $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'warga');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    /**
     * Setujui akun warga
     */
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = true;
        $user->save();

        // Kirim email notifikasi ke warga
        try {
            Mail::to($user->email)->send(new AccountApprovedMail($user));
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Akun warga berhasil disetujui.',
            'data' => $user
        ]);
    }

    /**
     * Streaming aman berkas KTP warga dari direktori privat
     */
    public function viewUserKtp($id)
    {
        $user = User::findOrFail($id);
        if (!$user->ktp_path || !Storage::disk('private')->exists($user->ktp_path)) {
            return response()->json(['message' => 'Dokumen KTP tidak ditemukan.'], 404);
        }

        return Storage::disk('private')->response($user->ktp_path);
    }

    /**
     * Daftar permohonan surat (opsional filter status: pending, approved, rejected)
     */
    public function getLetterRequests(Request $request)
    {
        $query = LetterRequest::with(['user', 'template'])->orderBy('created_at', 'desc');
        
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get()
        ]);
    }

    /**
     * Setujui permohonan surat & generate PDF ber-QR Code
     */
    public function approveLetterRequest($id)
    {
        $letterRequest = LetterRequest::with(['user', 'template'])->findOrFail($id);

        if ($letterRequest->status === 'approved' && $letterRequest->pdf_path) {
            return response()->json([
                'status' => 'success',
                'message' => 'Surat sudah disetujui sebelumnya.',
                'data' => $letterRequest
            ]);
        }

        // Generate unique token untuk validasi QR
        if (!$letterRequest->token) {
            $letterRequest->token = Str::uuid()->toString();
        }

        // Generate PDF sisi server via PdfController Helper
        $pdfPath = PdfController::generateLetterPdf($letterRequest);

        $letterRequest->status = 'approved';
        $letterRequest->pdf_path = $pdfPath;
        $letterRequest->save();

        // Kirim email notifikasi ke warga berisikan tautan download
        try {
            Mail::to($letterRequest->user->email)->send(new LetterReadyMail($letterRequest));
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Permohonan surat berhasil disetujui dan PDF telah diterbitkan.',
            'data' => $letterRequest
        ]);
    }

    /**
     * Tolak permohonan surat
     */
    public function rejectLetterRequest(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $letterRequest = LetterRequest::findOrFail($id);
        $letterRequest->status = 'rejected';
        $letterRequest->rejection_reason = $validated['rejection_reason'];
        $letterRequest->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Permohonan surat ditolak.',
            'data' => $letterRequest
        ]);
    }
}
