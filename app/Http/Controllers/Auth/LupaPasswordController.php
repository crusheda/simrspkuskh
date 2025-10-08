<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class LupaPasswordController extends Controller
{
    function index()
    {
        return view('pages.auth.lupapassword');
    }

    function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|numeric',
            'username' => 'required|string',
            'password' => 'required|min:6|confirmed', // validasi konfirmasi password
        ]);

        // Cari user berdasarkan NIP dan Username
        $user = Pengguna::where('NIP', $request->nip)
                        ->where('LOGIN', $request->username)
                        ->first();

        if (!$user) {
            return back()->withErrors(['error' => 'Data pengguna tidak ditemukan. NIP atau Username tidak cocok. Silakan Coba Lagi.']);
        }

        // Cek apakah password baru sama dengan password lama
        if (Hash::check($request->password, $user->PASSWORD)) {
            return back()->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama Anda.']);
        }

        // Update password (ikuti format login kamu)
        // Jika login kamu pakai kombinasi hash_hmac sebelum bcrypt, sesuaikan di sini
        $user->PASSWORD = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('message', 'Password berhasil diperbarui. Silakan login kembali menggunakan Username dan Password BARU Anda.');
    }
}
