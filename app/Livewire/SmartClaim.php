<?php

namespace App\Livewire;

use Livewire\Component;

class SmartClaim extends Component
{
    public function render()
    {
        return view('pages.v3.smartclaim.index')->layout('layouts.v3.app');
    }
}
