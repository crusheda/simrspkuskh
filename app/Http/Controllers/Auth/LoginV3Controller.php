
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
            $authenticated = $authService->authenticate(
                $request->name,
                $request->password
            );

            if (!$authenticated) {
                return back()->withErrors([
                    'name' => 'Username atau password salah.',
                ]);
            }

            return redirect()->route('v3.emr');

        } catch (RuntimeException $e) {
            return back()->withErrors([
                'name' => $e->getMessage(),
            ]);
        }
    }
}
