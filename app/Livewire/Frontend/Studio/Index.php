<?php

namespace App\Livewire\Frontend\Studio;

use App\Models\Cinema;
use App\Models\Studio;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public $cinemaId = '';

    public function render()
    {
        $cinemas = Cinema::with(['studios' => function ($query) {
            $query->withCount('seats');
        }])
        ->when($this->cinemaId, function ($query) {
            $query->where('id', $this->cinemaId);
        })
        ->get();

        $allCinemas = Cinema::select('id', 'name')->get();

        return view('livewire.frontend.studio.index', [
            'cinemas' => $cinemas,
            'allCinemas' => $allCinemas,
        ]);
    }
}
