<?php

namespace App\Http\Controllers\Api\Surat;

use App\Http\Controllers\Controller;
use App\Mail\Surat\NewLetterRequestMail;
use App\Models\LetterRequest;
use App\Models\LetterTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CitizenController extends Controller
{
    /**
     * Daftar semua template surat yang tersedia
     */
    public function getTemplates()
    {
        $templates = LetterTemplate::orderBy('name', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $templates
        ]);
    }

    /**
     * Detail satu template beserta skema required_fields
     */
    public function getTemplateById($id)
    {
        $template = LetterTemplate::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $template
        ]);
    }

    /**
     * Pengajuan surat baru oleh warga
     */
    public function submitRequest(Request $request)
    {
        $user = $request->user();

        // Pastikan akun warga sudah disetujui admin
        if (!$user->is_approved) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Anda sedang diverifikasi oleh Admin. Anda belum dapat mengajukan permohonan surat.'
            ], 403);
        }

        $validated = $request->validate([
            'template_id' => 'required|exists:letter_templates,id',
            'form_data' => 'required|array',
        ]);

        $letterRequest = LetterRequest::create([
            'user_id' => $user->id,
            'template_id' => $validated['template_id'],
            'form_data' => $validated['form_data'],
            'status' => 'pending',
        ]);

        $letterRequest->load(['user', 'template']);

        // Kirim email notifikasi ke Admin
        try {
            $adminEmail = env('ADMIN_SURAT_EMAIL', 'admin@mengeruda.id');
            Mail::to($adminEmail)->send(new NewLetterRequestMail($letterRequest));
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Permohonan surat berhasil dikirim dan sedang menunggu tinjauan Admin.',
            'data' => $letterRequest
        ], 201);
    }

    /**
     * Riwayat pengajuan surat warga yang sedang login
     */
    public function getMyRequests(Request $request)
    {
        $requests = LetterRequest::with('template')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $requests
        ]);
    }
}
