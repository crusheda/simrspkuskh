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
    public function authenticate(string $username, string $password): bool
    {
        // Periksa koneksi database aplikasi
        try {
            DB::connection('db_aplikasi')->getPdo();
        } catch (Throwable $e) {
            throw new RuntimeException(
                'Server database sedang tidak aktif. Silakan hubungi administrator.'
            );
        }

        // Cari pengguna berdasarkan LOGIN
        try {
            $user = Pengguna::where('LOGIN', $username)->first();
        } catch (Throwable $e) {
            throw new RuntimeException(
                'Terjadi gangguan saat mengakses database pengguna.'
            );
        }

        // Verifikasi username dan password
        if (!$user || !Hash::check($password, $user->PASSWORD)) {
            return false;
        }

        // Login pengguna
        Auth::login($user);

        // Regenerasi session untuk keamanan
        session()->regenerate();

        return true;
    }
}
