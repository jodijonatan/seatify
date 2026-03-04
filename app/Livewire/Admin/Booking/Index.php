<?php
namespace App\Livewire\Admin\Booking;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $bookings = Booking::with(['user', 'showtime.movie'])
            ->where('booking_code', 'like', '%' . $this->search . '%')
            ->orWhereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.booking.index', compact('bookings'));
    }

    public function markAsPaid($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'paid']);
        
        if ($booking->payment) {
            $booking->payment->update(['status' => 'success']);
        }

        session()->flash('message', 'Booking marked as paid.');
    }

    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'cancelled']);
        
        // Free up the seats
        foreach ($booking->seats as $bookingSeat) {
            $bookingSeat->update(['status' => 'available']);
        }

        if ($booking->payment) {
            $booking->payment->update(['status' => 'failed']);
        }

        session()->flash('message', 'Booking cancelled and seats freed.');
    }
}
