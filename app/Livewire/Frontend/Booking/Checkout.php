<?php

namespace App\Livewire\Frontend\Booking;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Checkout extends Component
{
    public $booking;

    public $paymentMethod = 'bank_transfer';

    public function mount($bookingId)
    {
        $this->booking = Booking::with(['showtime.movie', 'showtime.cinema', 'showtime.studio', 'seats.seat'])
            ->where('user_id', Auth::id())
            ->findOrFail($bookingId);

        // If booking is already paid or cancelled, redirect to history
        if ($this->booking->status !== 'pending') {
            return redirect()->route('booking.history');
        }
    }

    public function processPayment()
    {
        DB::beginTransaction();
        try {
            // Check if seats are still available (they should be "selected" by us, or at least still available if the flow broke)
            foreach ($this->booking->seats as $bSeat) {
                if ($bSeat->status === 'booked' && $bSeat->booking_id !== $this->booking->id) {
                    throw new \Exception('One or more seats have been taken. Please rebook.');
                }
            }

            // Mock Payment record
            Payment::create([
                'booking_id' => $this->booking->id,
                'amount' => $this->booking->total_price,
                'payment_method' => $this->paymentMethod,
                'status' => 'success',
            ]);

            // Mark booking as paid
            $this->booking->update(['status' => 'paid']);

            // Mark seats as booked
            foreach ($this->booking->seats as $bSeat) {
                $bSeat->update(['status' => 'booked']);
            }

            DB::commit();

            session()->flash('message', 'Payment successful! Here is your e-ticket.');

            return redirect()->route('booking.history', ['highlight' => $this->booking->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage() ?? 'Payment failed. Please try again.');
        }
    }

    public function cancelBooking()
    {
        DB::beginTransaction();
        try {
            $this->booking->update(['status' => 'cancelled']);

            // Delete booking seats to fully free them for re-booking
            $this->booking->seats()->delete();

            DB::commit();

            session()->flash('message', 'Booking cancelled successfully.');

            return redirect()->route('home');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error cancelling booking.');
        }
    }

    public function render()
    {
        return view('livewire.frontend.booking.checkout');
    }
}
