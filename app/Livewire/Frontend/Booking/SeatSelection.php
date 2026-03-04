<?php
namespace App\Livewire\Frontend\Booking;

use App\Models\Seat;
use App\Models\Booking;
use App\Models\Showtime;
use App\Models\BookingSeat;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class SeatSelection extends Component
{
    public $showtime;
    public $seats = [];
    public $bookedSeatIds = [];
    public $selectedSeatIds = [];

    public function mount($showtimeId)
    {
        $this->showtime = Showtime::with(['movie', 'cinema', 'studio'])->findOrFail($showtimeId);
        
        // Load all seats for the studio
        $this->seats = Seat::where('studio_id', $this->showtime->studio_id)
            ->orderBy('row')
            ->orderBy('number')
            ->get();
            
        // Load already booked or pending seats for this showtime
        $this->bookedSeatIds = BookingSeat::where('showtime_id', $this->showtime->id)
            ->whereIn('status', ['booked', 'selected'])
            ->pluck('seat_id')
            ->toArray();
    }

    public function toggleSeat($seatId)
    {
        if (in_array($seatId, $this->bookedSeatIds)) {
            // Cannot select a booked seat
            return;
        }

        if (in_array($seatId, $this->selectedSeatIds)) {
            // Deselect
            $this->selectedSeatIds = array_diff($this->selectedSeatIds, [$seatId]);
        } else {
            // Select (limit to max 6 seats per transaction to prevent abuse)
            if (count($this->selectedSeatIds) >= 6) {
                session()->flash('error', 'You can only select up to 6 seats per booking.');
                return;
            }
            $this->selectedSeatIds[] = $seatId;
        }
    }

    public function proceedToCheckout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (empty($this->selectedSeatIds)) {
            session()->flash('error', 'Please select at least one seat.');
            return;
        }

        // Before proceeding, double-check if any selected seat was booked concurrently
        $concurrentlyBooked = BookingSeat::where('showtime_id', $this->showtime->id)
            ->whereIn('seat_id', $this->selectedSeatIds)
            ->whereIn('status', ['booked', 'selected'])
            ->exists();

        if ($concurrentlyBooked) {
            session()->flash('error', 'Some of the seats you selected are no longer available. Please select different seats.');
            // Reload booked seats
            $this->bookedSeatIds = BookingSeat::where('showtime_id', $this->showtime->id)
                ->whereIn('status', ['booked', 'selected'])
                ->pluck('seat_id')
                ->toArray();
            $this->selectedSeatIds = [];
            return;
        }

        DB::beginTransaction();
        try {
            $totalPrice = $this->showtime->price * count($this->selectedSeatIds);
            
            // Create pending booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'showtime_id' => $this->showtime->id,
                'booking_code' => 'BKG-' . strtoupper(uniqid()),
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // Create booking seats
            foreach ($this->selectedSeatIds as $seatId) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'showtime_id' => $this->showtime->id,
                    'status' => 'selected', // Reserved but not paid
                ]);
            }

            DB::commit();

            return redirect()->route('booking.checkout', $booking->id);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'An error occurred while creating your booking. Please try again.');
        }
    }

    public function render()
    {
        // Group seats by row for easier grid rendering
        $groupedSeats = $this->seats->groupBy('row');
        $totalPrice = $this->showtime->price * count($this->selectedSeatIds);

        return view('livewire.frontend.booking.seat-selection', compact('groupedSeats', 'totalPrice'));
    }
}
