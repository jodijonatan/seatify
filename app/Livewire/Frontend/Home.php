<?php
namespace App\Livewire\Frontend;

use App\Models\Movie;
use Livewire\Component;
use Livewire\Attributes\Layout;

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
