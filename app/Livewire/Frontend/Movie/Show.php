<?php

namespace App\Livewire\Frontend\Movie;

use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public $movie;

    public function mount($id)
    {
        $this->movie = Movie::with(['showtimes.cinema', 'showtimes.studio'])->findOrFail($id);
    }

    public function render()
    {
        // Group showtimes by cinema and date for easier display
        $groupedShowtimes = $this->movie->showtimes
            ->where('show_date', '>=', now()->toDateString())
            ->groupBy(function ($showtime) {
                return $showtime->cinema->name;
            })->map(function ($cinemaShowtimes) {
                return $cinemaShowtimes->groupBy('show_date');
            });

        return view('livewire.frontend.movie.show', compact('groupedShowtimes'));
    }
}
