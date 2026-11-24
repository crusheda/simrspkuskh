<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\LoginV3Service;
use Illuminate\Http\Request;
use RuntimeException;

class LoginV3Controller extends Controller
{
    public function login(Request $request, LoginV3Service $authService)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ], [
            'name.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        try {
            $result = $authService->authenticate(
                $request->name,
                $request->password
            );

            // Login gagal
            if (!$result['success']) {
                return back()
                    ->withInput($request->only('name'))
                    ->withErrors([
                        'name' => $result['message'],
                    ]);
            }

            // Login berhasil
            return redirect()->route('v3.emr');

        } catch (RuntimeException $e) {
            return back()
                ->withInput($request->only('name'))
                ->withErrors([
                    'name' => $e->getMessage(),
                ]);
        }
    }
}
