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

    public string $bookingSearch = '';

    /**
     * Available values: all, paid, pending, cancelled.
     */
    public string $statusFilter = 'all';

    public function mount()
    {
        $this->highlight = request()->query('highlight');
    }

    public function setStatusFilter(string $status): void
    {
        $allowed = ['all', 'paid', 'pending', 'cancelled'];
        if (! in_array($status, $allowed, true)) {
            $status = 'all';
        }

        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function updatingBookingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $bookingsBase = Booking::query()
            ->with(['showtime.movie', 'showtime.cinema', 'showtime.studio', 'seats.seat'])
            ->where('user_id', Auth::id());

        $paidCount = (clone $bookingsBase)->where('status', 'paid')->count();
        $pendingCount = (clone $bookingsBase)->where('status', 'pending')->count();
        $cancelledCount = (clone $bookingsBase)->where('status', 'cancelled')->count();

        if ($this->statusFilter !== 'all') {
            $bookingsBase->where('status', $this->statusFilter);
        }

        if ($this->bookingSearch !== '') {
            $search = '%'.$this->bookingSearch.'%';

            $bookingsBase->where(function ($query) use ($search) {
                $query->where('booking_code', 'like', $search)
                    ->orWhereHas('showtime.movie', function ($movieQuery) use ($search) {
                        $movieQuery->where('title', 'like', $search);
                    })
                    ->orWhereHas('showtime.cinema', function ($cinemaQuery) use ($search) {
                        $cinemaQuery->where('name', 'like', $search);
                    })
                    ->orWhereHas('showtime.studio', function ($studioQuery) use ($search) {
                        $studioQuery->where('name', 'like', $search);
                    });
            });
        }

        $bookings = $bookingsBase
            ->latest()
            ->paginate(10);

        return view('livewire.frontend.booking.history', compact(
            'bookings',
            'paidCount',
            'pendingCount',
            'cancelledCount',
        ));
    }
}
