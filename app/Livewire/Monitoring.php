<?php

namespace App\Livewire;

use Livewire\Component;

class Monitoring extends Component
{
    public function render()
    {
        return view('pages.v3.monitoring.index')->layout('layouts.v3.app');
    }
}
