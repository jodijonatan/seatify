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
    public string $bookingSearch = '';

    /**
     * Available values: all, paid, pending, cancelled.
     */
    public string $bookingStatusFilter = 'all';

    public bool $isBookingModalOpen = false;

    public ?int $bookingIdForModal = null;

    public function setBookingStatusFilter(string $status): void
    {
        $allowed = ['all', 'paid', 'pending', 'cancelled'];
        if (! in_array($status, $allowed, true)) {
            $status = 'all';
        }

        $this->bookingStatusFilter = $status;
    }

    public function openBookingModal(int $bookingId): void
    {
        $this->bookingIdForModal = $bookingId;
        $this->isBookingModalOpen = true;
    }

    public function closeBookingModal(): void
    {
        $this->isBookingModalOpen = false;
        $this->bookingIdForModal = null;
    }

    public function render(): \Illuminate\View\View
    {
        $paidBookingsCount = Booking::where('status', 'paid')->count();
        $pendingBookingsCount = Booking::where('status', 'pending')->count();
        $cancelledBookingsCount = Booking::where('status', 'cancelled')->count();

        $totalRevenuePaid = Booking::where('status', 'paid')->sum('total_price');

        $totalMoviesShowing = Movie::where('status', 'showing')->count();
        $totalCinemas = Cinema::count();

        $totalBookingsAll = $paidBookingsCount + $pendingBookingsCount + $cancelledBookingsCount;
        $paidRate = $totalBookingsAll > 0 ? ($paidBookingsCount / $totalBookingsAll) : 0.0;

        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        $revenueTrendRows = Booking::query()
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as day, SUM(total_price) as revenue')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('day')
            ->get();

        $revenueByDay = $revenueTrendRows->pluck('revenue', 'day');
        $revenueMax = (float) $revenueByDay->max() ?: 1.0;

        $revenueTrend = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startDate->copy()->addDays($i);
            $dayKey = $day->toDateString();
            $revenue = (float) ($revenueByDay[$dayKey] ?? 0);

            $revenueTrend[] = [
                'day' => $dayKey,
                'label' => $day->format('d M'),
                'revenue' => $revenue,
                'heightPct' => $revenueMax > 0 ? (($revenue / $revenueMax) * 100) : 0,
            ];
        }

        $topMovies = Movie::query()
            ->join('showtimes', 'movies.id', '=', 'showtimes.movie_id')
            ->join('booking_seats', 'showtimes.id', '=', 'booking_seats.showtime_id')
            ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid')
            ->where('booking_seats.status', 'booked')
            ->select('movies.id', 'movies.title')
            ->selectRaw('COUNT(booking_seats.id) as tickets_sold')
            ->groupBy('movies.id', 'movies.title')
            ->orderByDesc('tickets_sold')
            ->limit(5)
            ->get();

        $topTicketsMax = (int) ($topMovies->max('tickets_sold') ?: 1);

        $bookingsQuery = Booking::query()
            ->with(['user', 'showtime.movie'])
            ->withCount('seats')
            ->latest();

        if ($this->bookingStatusFilter !== 'all') {
            $bookingsQuery->where('status', $this->bookingStatusFilter);
        }

        if ($this->bookingSearch !== '') {
            $search = '%'.$this->bookingSearch.'%';

            $bookingsQuery->where(function ($query) use ($search) {
                $query->where('booking_code', 'like', $search)
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', $search);
                    })
                    ->orWhereHas('showtime.movie', function ($movieQuery) use ($search) {
                        $movieQuery->where('title', 'like', $search);
                    });
            });
        }

        $recentBookings = $bookingsQuery->take(10)->get();

        $selectedBooking = null;
        if ($this->isBookingModalOpen && $this->bookingIdForModal !== null) {
            $selectedBooking = Booking::with([
                'user',
                'payment',
                'showtime.movie',
                'showtime.cinema',
                'showtime.studio',
                'seats.seat',
            ])->find($this->bookingIdForModal);
        }

        return view('livewire.admin.dashboard', compact(
            'paidBookingsCount',
            'pendingBookingsCount',
            'cancelledBookingsCount',
            'totalRevenuePaid',
            'totalMoviesShowing',
            'totalCinemas',
            'paidRate',
            'revenueTrend',
            'revenueMax',
            'topMovies',
            'topTicketsMax',
            'recentBookings',
            'selectedBooking',
        ));
    }
}
