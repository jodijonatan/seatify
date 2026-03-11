<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Cinema;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $totalBookings = Booking::where('status', 'paid')->count();
        $totalRevenue = Booking::where('status', 'paid')->sum('total_price');
        $totalMovies = Movie::where('status', 'showing')->count();
        $totalCinemas = Cinema::count();

        $recentBookings = Booking::with(['user', 'showtime.movie'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', compact(
            'totalBookings',
            'totalRevenue',
            'totalMovies',
            'totalCinemas',
            'recentBookings'
        ));
    }
}
