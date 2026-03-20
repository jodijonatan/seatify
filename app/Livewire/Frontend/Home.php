<?php

namespace App\Livewire\Frontend;

use App\Models\Booking;
use App\Models\Cinema;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Home extends Component
{
    public function render()
    {
        $movies = Movie::where('status', 'showing')->get();
        $upcomingMovies = Movie::where('status', 'upcoming')->get();
        $cinemas = Cinema::all();

        $dashboard = null;

        if (Auth::check()) {
            $paidCount = Booking::where('user_id', Auth::id())->where('status', 'paid')->count();
            $pendingCount = Booking::where('user_id', Auth::id())->where('status', 'pending')->count();
            $cancelledCount = Booking::where('user_id', Auth::id())->where('status', 'cancelled')->count();

            $nextBooking = Booking::query()
                ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
                ->where('bookings.user_id', Auth::id())
                ->whereIn('bookings.status', ['paid', 'pending'])
                ->where('showtimes.show_date', '>=', now()->toDateString())
                ->orderBy('showtimes.show_date')
                ->orderBy('showtimes.start_time')
                ->select('bookings.*')
                ->with(['showtime.movie', 'showtime.cinema', 'showtime.studio', 'seats.seat'])
                ->withCount('seats')
                ->first();

            $dashboard = [
                'paidCount' => $paidCount,
                'pendingCount' => $pendingCount,
                'cancelledCount' => $cancelledCount,
                'nextBooking' => $nextBooking,
            ];
        }

        return view('livewire.frontend.home', compact('movies', 'upcomingMovies', 'cinemas', 'dashboard'));
    }
}
