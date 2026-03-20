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
        $availableShowtimes = $this->movie->showtimes
            ->where('show_date', '>=', now()->toDateString())
            ->sortBy(fn ($showtime) => $showtime->show_date.' '.$showtime->start_time);

        $nextShowtime = $availableShowtimes->first();

        $groupedShowtimes = $availableShowtimes
            ->groupBy(function ($showtime) {
                return $showtime->cinema->name;
            })->map(function ($cinemaShowtimes) {
                return $cinemaShowtimes->groupBy('show_date');
            });

        return view('livewire.frontend.movie.show', [
            'groupedShowtimes' => $groupedShowtimes,
            'nextShowtime' => $nextShowtime,
            'availableShowtimesCount' => $availableShowtimes->count(),
        ]);
    }
}
