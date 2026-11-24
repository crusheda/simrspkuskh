<?php

namespace App\Services\Auth;

use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Throwable;

class LoginV3Service
{
    public function authenticate(string $username, string $password): array
    {
        // =====================================================
        // CEK KONEKSI DATABASE
        // =====================================================
        try {
            DB::connection('db_aplikasi')->getPdo();
        } catch (Throwable $e) {
            throw new RuntimeException(
                'Server database sedang tidak aktif. Silakan hubungi administrator.'
            );
        }

        // =====================================================
        // CARI PENGGUNA
        // =====================================================
        try {
            $user = Pengguna::where('LOGIN', $username)->first();
        } catch (Throwable $e) {
            throw new RuntimeException(
                'Terjadi gangguan saat mengakses database pengguna.'
            );
        }

        // =====================================================
        // USER TIDAK DITEMUKAN
        // =====================================================
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Username atau password yang Anda masukkan tidak sesuai.',
            ];
        }

        // =====================================================
        // AKUN TIDAK AKTIF
        // =====================================================
        if ((int) $user->STATUS !== 1) {
            return [
                'success' => false,
                'message' => 'Akun Anda Dinonaktifkan. Silakan hubungi administrator.',
            ];
        }

        // =====================================================
        // VERIFIKASI PASSWORD
        // =====================================================
        if (!Hash::check($password, $user->PASSWORD)) {
            return [
                'success' => false,
                'message' => 'Username atau password yang Anda masukkan tidak sesuai.',
            ];
        }

        // =====================================================
        // LOGIN
        // =====================================================
        Auth::login($user);

        // Regenerasi session untuk keamanan
        session()->regenerate();

        return [
            'success' => true,
            'message' => 'Login berhasil.',
        ];
    }
}
