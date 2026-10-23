<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

class HandleTokenMismatch
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {

            // Jika request AJAX atau API, kembalikan JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sesi login kadaluarsa, silakan login kembali.',
                ], 419);
            }

            // Tentukan route login berdasarkan URL yang diakses
            $loginRoute = $request->is('v3/*') || $request->is('v3')
                ? 'v3.login'
                : 'login';

            return redirect()
                ->route($loginRoute)
                ->with('error', 'Sesi login kadaluarsa, silakan login kembali.');
        }
    }
}
