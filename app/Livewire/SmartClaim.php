<?php

namespace App\Livewire;

use Livewire\Component;

class SmartClaim extends Component
{
    public function render()
    {
        return view('livewire.smartclaim.index')->layout('layouts.v3.app');
    }
}
