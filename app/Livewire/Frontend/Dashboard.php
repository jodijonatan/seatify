<?php

namespace App\Livewire\Frontend;

use App\Models\Booking;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'pending_bookings' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('user_id', $user->id)->where('status', 'paid')->count(),
        ];

        $recentBookings = Booking::with(['showtime.movie', 'showtime.cinema', 'showtime.studio'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $recommendedMovies = Movie::where('status', 'showing')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('livewire.frontend.dashboard', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'recommendedMovies' => $recommendedMovies,
        ]);
    }
}
