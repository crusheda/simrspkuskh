<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirectRoute('v3.dashboard', navigate: false);
        }
    }

    public function render()
    {
        return view('pages.v3.auth.login')
            ->layout('layouts.v3.auth.index');
    }
}
