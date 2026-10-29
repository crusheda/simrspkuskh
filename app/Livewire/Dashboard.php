<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('pages.v3.dashboard.index')->layout('layouts.v3.app');
    }
}
