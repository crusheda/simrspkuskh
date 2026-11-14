<?php

namespace App\Livewire\Auth;

use App\Services\Auth\LoginV3Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;
use RuntimeException;

class Login extends Component
{
    public string $name = '';

    public string $password = '';

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirectByPermission();
            // $this->redirectRoute('v3.emr', navigate: false);
        }
    }

    public function login(LoginV3Service $authService): void
    {
        $this->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ], [
            'name.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Rate limit login berdasarkan username dan IP
        $key = Str::lower($this->name) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError(
                'name',
                'Terlalu banyak percobaan login. Silakan coba kembali dalam '
                . RateLimiter::availableIn($key)
                . ' detik.'
            );

            return;
        }

        try {
            $authenticated = $authService->authenticate(
                $this->name,
                $this->password
            );

            if (!$authenticated) {
                RateLimiter::hit($key, 60);

                $this->addError(
                    'name',
                    'Username atau password salah.'
                );

                return;
            }

            RateLimiter::clear($key);

            // Login berhasil
            // $this->redirectRoute('v3.emr', navigate: false);
            $this->redirectByPermission();

        } catch (RuntimeException $e) {
            $this->addError('name', $e->getMessage());
        }
    }

    public function render()
    {
        return view('pages.v3.auth.login')
            ->layout('layouts.v3.auth.index');
    }

    private function redirectByPermission(): void
    {
        $user = Auth::user();

        if ($user && $user->hasPermissionTo('emr')) {
            $this->redirectRoute('v3.emr', navigate: false);
        } else {
            $this->redirectRoute('v3.dashboard', navigate: false);
        }
    }
}
