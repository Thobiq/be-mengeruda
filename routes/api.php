<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Tourism\TourismAttractionController;
use App\Http\Controllers\Api\Tourism\TourismUmkmController;
use App\Http\Controllers\Api\Tourism\TourismProductController;
use App\Http\Controllers\Api\Tourism\TourismNewsController;
use App\Http\Controllers\Api\Tourism\TourismGalleryController;
use App\Http\Controllers\Api\Tourism\TourismEventController;
use App\Http\Controllers\Api\Tourism\TourismProfileController;
// use App\Http\Controllers\Api\Tourism\AuthController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Controllers\Api\AuthController;

// =======================
// Rute Autentikasi Mandiri
// =======================
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

// =======================
// Rute Publik (Read-Only)
// =======================

// 1. Tempat Wisata
Route::get('/attractions', [TourismAttractionController::class, 'index']);
Route::get('/attractions/{id}', [TourismAttractionController::class, 'show']);

// 2. Toko UMKM
Route::get('/umkms', [TourismUmkmController::class, 'index']);
Route::get('/umkms/{id}', [TourismUmkmController::class, 'show']);
Route::get('/umkms/{umkmId}/products', [TourismProductController::class, 'index']);

// 3. Produk UMKM
Route::get('/products', [TourismProductController::class, 'index']);
Route::get('/products/{id}', [TourismProductController::class, 'show']);

// 4. Berita Pariwisata
Route::get('/news', [TourismNewsController::class, 'index']);
Route::get('/news/{idOrSlug}', [TourismNewsController::class, 'show']);

// 5. Galeri Pariwisata
Route::get('/galleries', [TourismGalleryController::class, 'index']);
Route::get('/galleries/{id}', [TourismGalleryController::class, 'show']);

// 6. Agenda / Kegiatan
Route::get('/events', [TourismEventController::class, 'index']);
Route::get('/events/{id}', [TourismEventController::class, 'show']);

// 7. Profil Pariwisata
Route::get('/profile', [TourismProfileController::class, 'show']);

// ==========================================
// Rute Terproteksi (Admin - Auth via Sanctum)
// ==========================================
Route::middleware(['auth:sanctum'])->group(function () {
    // 1. Tempat Wisata CRUD
    Route::post('/attractions', [TourismAttractionController::class, 'store']);
    Route::put('/attractions/{id}', [TourismAttractionController::class, 'update']);
    Route::delete('/attractions/{id}', [TourismAttractionController::class, 'destroy']);

    // 2. Toko UMKM CRUD
    Route::post('/umkms', [TourismUmkmController::class, 'store']);
    Route::put('/umkms/{id}', [TourismUmkmController::class, 'update']);
    Route::delete('/umkms/{id}', [TourismUmkmController::class, 'destroy']);

    // 3. Produk UMKM CRUD
    Route::post('/products', [TourismProductController::class, 'store']);
    Route::put('/products/{id}', [TourismProductController::class, 'update']);
    Route::delete('/products/{id}', [TourismProductController::class, 'destroy']);

    // 4. Berita Pariwisata CRUD
    Route::post('/news', [TourismNewsController::class, 'store']);
    Route::put('/news/{id}', [TourismNewsController::class, 'update']);
    Route::delete('/news/{id}', [TourismNewsController::class, 'destroy']);

    // 5. Galeri Pariwisata CRUD
    Route::post('/galleries', [TourismGalleryController::class, 'store']);
    Route::put('/galleries/{id}', [TourismGalleryController::class, 'update']);
    Route::delete('/galleries/{id}', [TourismGalleryController::class, 'destroy']);

    // 6. Agenda / Kegiatan CRUD
    Route::post('/events', [TourismEventController::class, 'store']);
    Route::put('/events/{id}', [TourismEventController::class, 'update']);
    Route::delete('/events/{id}', [TourismEventController::class, 'destroy']);

    // 7. Profil Pariwisata Update (PUT dan POST untuk wsp upload FormData)
    Route::put('/profile', [TourismProfileController::class, 'update']);
    Route::post('/profile', [TourismProfileController::class, 'update']);

    // 8. Upload Gambar untuk Quill JS / Content Editor
    Route::post('/upload-image', [ImageUploadController::class, 'upload']);
});
