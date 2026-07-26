<?php

namespace App\Http\Controllers\Api\Surat;

use App\Http\Controllers\Controller;
use App\Mail\Surat\NewRegistrationMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Pendaftaran warga baru dengan upload KTP
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:users,nik',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'ktp' => 'required|file|mimes:jpeg,jpg,png,pdf|max:4096',
        ]);

        // Simpan KTP di storage privat
        $ktpPath = $request->file('ktp')->store('ktp', 'private');

        $user = User::create([
            'nik' => $validated['nik'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'ktp_path' => $ktpPath,
            'is_approved' => false,
        ]);

        // Assign role 'warga'
        $wargaRole = Role::where('name', 'warga')->first();
        if ($wargaRole) {
            $user->roles()->attach($wargaRole->id);
        }

        // Kirim email notifikasi ke admin
        try {
            $adminEmail = env('ADMIN_SURAT_EMAIL', 'admin@mengeruda.id');
            Mail::to($adminEmail)->send(new NewRegistrationMail($user));
        } catch (\Exception $e) {
            // Log jika email gagal dikirim (tidak memblokir pendaftaran)
            report($e);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pendaftaran berhasil. Akun Anda sedang diverifikasi oleh Admin.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_approved' => $user->is_approved,
            ]
        ], 201);
    }

    /**
     * Login SvelteKit via Sanctum Shared Cookies / SPA Authentication
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password tidak sesuai.'
            ], 401);
        }

        $request->session()->regenerate();
        $user = Auth::user();
        $user->load('roles');

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nik' => $user->nik,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'is_approved' => (bool) $user->is_approved,
                    'roles' => $user->roles->pluck('name'),
                ]
            ]
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil.'
        ]);
    }

    /**
     * Get Current User (Me)
     */
    public function me(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }
        $user->load('roles');

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'nik' => $user->nik,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'is_approved' => (bool) $user->is_approved,
                'roles' => $user->roles->pluck('name'),
            ]
        ]);
    }
}
