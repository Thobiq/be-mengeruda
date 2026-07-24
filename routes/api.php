<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VillageProfileController;

use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Controllers\Api\DemographicController;
use App\Http\Controllers\Api\ApbDesaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\PerangkatDesaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Struktur Desa (Publik)
Route::get('/struktur-desa', [PerangkatDesaController::class, 'index']);

// Struktur Desa (Admin - dilindungi auth/sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/struktur-desa', [PerangkatDesaController::class, 'store']);
    Route::post('/struktur-desa/{id}', [PerangkatDesaController::class, 'update']); // Pakai POST karena ada upload gambar (form-data)
    Route::delete('/struktur-desa/{id}', [PerangkatDesaController::class, 'destroy']);
});

// Peta Desa Routes
use App\Http\Controllers\Api\MapLocationController;
Route::get('/map-locations', [MapLocationController::class, 'index']);
Route::post('/map-locations/import', [MapLocationController::class, 'importGeoJson']);
Route::get('/map-locations/{id}', [MapLocationController::class, 'show']);
Route::post('/map-locations', [MapLocationController::class, 'store']);
Route::post('/map-locations/{id}', [MapLocationController::class, 'update']); // use post with _method=PUT or just post for multipart
Route::delete('/map-locations/{id}', [MapLocationController::class, 'destroy']);

// APB Desa Routes
Route::get('/apb-desa', [ApbDesaController::class, 'index']);
Route::get('/apb-desa/{year}', [ApbDesaController::class, 'show']);
Route::post('/apb-desa', [ApbDesaController::class, 'storeOrUpdate']);
Route::delete('/apb-desa/{year}', [ApbDesaController::class, 'destroy']);

// Rute Profil Desa
Route::get('/village-profile', [VillageProfileController::class, 'index']);
Route::post('/village-profile/info', [VillageProfileController::class, 'updateInfo']);
Route::post('/village-profile/narasi', [VillageProfileController::class, 'updateNarasi']);

// Rute Berita Desa
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slug}', [NewsController::class, 'show']);
Route::post('/news', [NewsController::class, 'store']);

// Rute Image Upload (Quill JS)
Route::post('/upload-image', [ImageUploadController::class, 'upload']);

// Rute Galeri Desa
use App\Http\Controllers\Api\GalleryController;
Route::get('/galleries', [GalleryController::class, 'index']);
Route::get('/galleries/{gallery}', [GalleryController::class, 'show']);

// Rute Demografi Penduduk
Route::get('/demographic', [DemographicController::class, 'index']);
Route::post('/demographic', [DemographicController::class, 'storeOrUpdate']);

// Rute Manajemen Administrator (User, Role, Permission)
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;

Route::middleware('auth:sanctum')->group(function () {
    // Manajemen Pengguna
    Route::get('/admin/users', [AdminUserController::class, 'index']);
    Route::post('/admin/users', [AdminUserController::class, 'store']);
    Route::put('/admin/users/{id}', [AdminUserController::class, 'update']);
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy']);

    // Manajemen Peran
    Route::get('/admin/roles', [RoleController::class, 'index']);
    Route::post('/admin/roles', [RoleController::class, 'store']);
    Route::put('/admin/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/admin/roles/{id}', [RoleController::class, 'destroy']);

    // Permissions
    Route::get('/admin/permissions', [PermissionController::class, 'index']);

    // Galeri Desa (Admin)
    Route::post('/galleries', [GalleryController::class, 'store']);
    Route::put('/galleries/{gallery}', [GalleryController::class, 'update']);
    Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy']);
});
