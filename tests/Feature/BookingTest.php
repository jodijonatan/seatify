<?php

use App\Livewire\Frontend\Booking\Checkout;
use App\Livewire\Frontend\Booking\SeatSelection;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Studio;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->cinema = Cinema::factory()->create();
    $this->studio = Studio::factory()->create([
        'cinema_id' => $this->cinema->id,
        'capacity' => 50,
    ]);
    $this->movie = Movie::factory()->create();
    $this->showtime = Showtime::factory()->create([
        'movie_id' => $this->movie->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
    ]);

    // Create some seats for this studio
    $this->seat1 = Seat::factory()->create(['studio_id' => $this->studio->id, 'row' => 'A', 'number' => 1]);
    $this->seat2 = Seat::factory()->create(['studio_id' => $this->studio->id, 'row' => 'A', 'number' => 2]);
    $this->seat3 = Seat::factory()->create(['studio_id' => $this->studio->id, 'row' => 'A', 'number' => 3]);

    $this->user = User::factory()->create();
});

it('redirects guest to login when visiting seat-selection page', function () {
    $this->get(route('booking.seat-selection', $this->showtime->id))
        ->assertRedirect(route('login'));
});

it('allows authenticated user to view seat selection', function () {
    $this->actingAs($this->user)
        ->get(route('booking.seat-selection', $this->showtime->id))
        ->assertSuccessful()
        ->assertSeeLivewire(SeatSelection::class);
});

it('allows authenticated user to select seats and proceed to checkout', function () {
    $this->actingAs($this->user);

    Livewire::test(SeatSelection::class, ['showtimeId' => $this->showtime->id])
        ->call('toggleSeat', $this->seat1->id)
        ->call('toggleSeat', $this->seat2->id)
        ->assertSet('selectedSeatIds', [$this->seat1->id, $this->seat2->id])
        ->call('proceedToCheckout')
        ->assertRedirect(); // Should redirect to checkout

    // Assert booking was created in DB
    $booking = Booking::where('user_id', $this->user->id)->first();
    expect($booking)->not->toBeNull();
    expect($booking->status)->toBe('pending');

    // Assert booking seats were created
    $bookingSeats = BookingSeat::where('booking_id', $booking->id)->get();
    expect($bookingSeats)->toHaveCount(2);
    expect($bookingSeats->pluck('seat_id')->toArray())->toEqualCanonicalizing([$this->seat1->id, $this->seat2->id]);
    expect($bookingSeats->first()->status)->toBe('selected');
});

it('does not allow selecting a seat that is already booked', function () {
    // Pre-create a booked seat by another user
    $otherUser = User::factory()->create();
    $booking = Booking::create([
        'user_id' => $otherUser->id,
        'showtime_id' => $this->showtime->id,
        'booking_code' => 'BKG-TEST',
        'total_price' => $this->showtime->price,
        'status' => 'paid',
    ]);
    BookingSeat::create([
        'booking_id' => $booking->id,
        'seat_id' => $this->seat1->id,
        'showtime_id' => $this->showtime->id,
        'status' => 'booked',
    ]);

    $this->actingAs($this->user);

    Livewire::test(SeatSelection::class, ['showtimeId' => $this->showtime->id])
        // bookedSeatIds should contain seat1
        ->assertSee($this->seat1->number) // Verify seat renders
        ->call('toggleSeat', $this->seat1->id) // Try to toggle the booked seat
        ->assertSet('selectedSeatIds', []) // Should remain empty
        ->call('toggleSeat', $this->seat2->id) // Try valid seat
        ->assertSet('selectedSeatIds', [$this->seat2->id]);
});

it('completes payment successfully and marks seats as booked', function () {
    $this->actingAs($this->user);

    $booking = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $this->showtime->id,
        'booking_code' => 'BKG-TEST',
        'total_price' => $this->showtime->price * 2,
        'status' => 'pending',
    ]);

    $bSeat1 = BookingSeat::create([
        'booking_id' => $booking->id,
        'seat_id' => $this->seat1->id,
        'showtime_id' => $this->showtime->id,
        'status' => 'selected',
    ]);

    Livewire::test(Checkout::class, ['bookingId' => $booking->id])
        ->set('paymentMethod', 'bank_transfer')
        ->call('processPayment')
        ->assertRedirect(route('booking.history', ['highlight' => $booking->id]));

    // Check DB status
    $booking->refresh();
    expect($booking->status)->toBe('paid');

    $bSeat1->refresh();
    expect($bSeat1->status)->toBe('booked');
});

it('deletes booking seat records when cancelled so seats are freed', function () {
    $this->actingAs($this->user);

    $booking = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $this->showtime->id,
        'booking_code' => 'BKG-TEST',
        'total_price' => $this->showtime->price,
        'status' => 'pending',
    ]);

    $bSeat = BookingSeat::create([
        'booking_id' => $booking->id,
        'seat_id' => $this->seat1->id,
        'showtime_id' => $this->showtime->id,
        'status' => 'selected',
    ]);

    Livewire::test(Checkout::class, ['bookingId' => $booking->id])
        ->call('cancelBooking')
        ->assertRedirect(route('home'));

    $booking->refresh();
    expect($booking->status)->toBe('cancelled');

    // BookingSeat record should be deleted so re-booking is possible without constraint errors
    $exists = BookingSeat::where('booking_id', $booking->id)->exists();
    expect($exists)->toBeFalse();
});
