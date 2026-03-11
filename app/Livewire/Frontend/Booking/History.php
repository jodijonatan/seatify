<?php

namespace App\Livewire\Frontend\Booking;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class History extends Component
{
    use WithPagination;

    public $highlight; // To highlight a newly created booking

    public function mount()
    {
        $this->highlight = request()->query('highlight');
    }

    public function render()
    {
        $bookings = Booking::with(['showtime.movie', 'showtime.cinema', 'showtime.studio', 'seats.seat'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.frontend.booking.history', compact('bookings'));
    }
}
