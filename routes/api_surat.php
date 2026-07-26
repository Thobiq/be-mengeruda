<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Surat\AuthController;
use App\Http\Controllers\Api\Surat\AdminController;
use App\Http\Controllers\Api\Surat\CitizenController;
use App\Http\Controllers\Api\Surat\PdfController;

Route::prefix('surat')->group(function () {
    // === Rute Publik ===
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/validasi', [PdfController::class, 'validateToken']);
    Route::get('/templates', [CitizenController::class, 'getTemplates']);
    Route::get('/templates/{id}', [CitizenController::class, 'getTemplateById']);

    // === Rute Wajib Login (Sanctum) ===
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/pdf/download/{id}', [PdfController::class, 'downloadPdf']);

        // --- Rute Warga ---
        Route::get('/permohonan/my', [CitizenController::class, 'getMyRequests']);
        Route::post('/permohonan', [CitizenController::class, 'submitRequest']);

        // --- Rute Admin (Verifikasi Akun & Surat) ---
        Route::prefix('admin')->group(function () {
            Route::get('/users/pending', [AdminController::class, 'getPendingUsers']);
            Route::get('/users/all', [AdminController::class, 'getAllUsers']);
            Route::post('/users/{id}/approve', [AdminController::class, 'approveUser']);
            Route::get('/users/{id}/ktp', [AdminController::class, 'viewUserKtp']);

            Route::get('/surat', [AdminController::class, 'getLetterRequests']);
            Route::post('/surat/{id}/approve', [AdminController::class, 'approveLetterRequest']);
            Route::post('/surat/{id}/reject', [AdminController::class, 'rejectLetterRequest']);
        });
    });
});
