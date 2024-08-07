<?php

namespace App\Http\Livewire\Parkings;

use Livewire\Component;
use App\Models\Parking;

class ParkingIndex extends Component
{
    public $parkings;

    public function mount()
    {
        $this->parkings = Parking::all();
    }

    public function render()
    {
        return view('livewire.parkings.index');
    }
}
