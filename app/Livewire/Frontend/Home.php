<?php

namespace App\Livewire\Frontend;

use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Home extends Component
{
    public function render()
    {
        $movies = Movie::where('status', 'showing')->get();
        $upcomingMovies = Movie::where('status', 'upcoming')->get();

        return view('livewire.frontend.home', compact('movies', 'upcomingMovies'));
    }
}
