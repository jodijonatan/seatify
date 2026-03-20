<?php

use App\Livewire\Admin\Dashboard;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Payment;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Studio;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->cinema = Cinema::factory()->create();
    $this->studio = Studio::factory()->create([
        'cinema_id' => $this->cinema->id,
        'capacity' => 50,
    ]);

    $this->seatA1 = Seat::factory()->create([
        'studio_id' => $this->studio->id,
        'row' => 'A',
        'number' => 1,
    ]);

    $this->seatA2 = Seat::factory()->create([
        'studio_id' => $this->studio->id,
        'row' => 'A',
        'number' => 2,
    ]);
});

it('renders KPI cards and top movies (with revenue max)', function () {
    $this->actingAs($this->user);

    $movie1 = Movie::factory()->create([
        'title' => 'Movie One',
        'status' => 'showing',
    ]);
    $movie2 = Movie::factory()->create([
        'title' => 'Movie Two',
        'status' => 'showing',
    ]);

    $showtime1 = Showtime::factory()->create([
        'movie_id' => $movie1->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
    ]);

    $showtime2 = Showtime::factory()->create([
        'movie_id' => $movie2->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
    ]);

    $paidBooking1 = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime1->id,
        'booking_code' => 'BKG-PAID-1',
        'total_price' => 50000,
        'status' => 'paid',
    ]);
    $paidBooking1->forceFill([
        'created_at' => Carbon::now()->subDays(1),
        'updated_at' => Carbon::now()->subDays(1),
    ])->save();

    BookingSeat::create([
        'booking_id' => $paidBooking1->id,
        'seat_id' => $this->seatA1->id,
        'showtime_id' => $showtime1->id,
        'status' => 'booked',
    ]);

    BookingSeat::create([
        'booking_id' => $paidBooking1->id,
        'seat_id' => $this->seatA2->id,
        'showtime_id' => $showtime1->id,
        'status' => 'booked',
    ]);

    Payment::create([
        'booking_id' => $paidBooking1->id,
        'amount' => 50000,
        'status' => 'success',
        'payment_method' => 'bank_transfer',
    ]);

    $paidBooking2 = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime2->id,
        'booking_code' => 'BKG-PAID-2',
        'total_price' => 30000,
        'status' => 'paid',
    ]);
    $paidBooking2->forceFill([
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ])->save();

    BookingSeat::create([
        'booking_id' => $paidBooking2->id,
        'seat_id' => $this->seatA1->id,
        'showtime_id' => $showtime2->id,
        'status' => 'booked',
    ]);

    Payment::create([
        'booking_id' => $paidBooking2->id,
        'amount' => 30000,
        'status' => 'success',
        'payment_method' => 'bank_transfer',
    ]);

    $pendingBooking = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime2->id,
        'booking_code' => 'BKG-PENDING-1',
        'total_price' => 15000,
        'status' => 'pending',
    ]);
    $pendingBooking->forceFill([
        'created_at' => Carbon::now()->subDays(2),
        'updated_at' => Carbon::now()->subDays(2),
    ])->save();

    BookingSeat::create([
        'booking_id' => $pendingBooking->id,
        'seat_id' => $this->seatA1->id,
        'showtime_id' => $showtime2->id,
        'status' => 'selected',
    ]);

    $cancelledBooking = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime2->id,
        'booking_code' => 'BKG-CANCELLED-1',
        'total_price' => 20000,
        'status' => 'cancelled',
    ]);
    $cancelledBooking->forceFill([
        'created_at' => Carbon::now()->subDays(3),
        'updated_at' => Carbon::now()->subDays(3),
    ])->save();

    BookingSeat::create([
        'booking_id' => $cancelledBooking->id,
        'seat_id' => $this->seatA2->id,
        'showtime_id' => $showtime2->id,
        'status' => 'selected',
    ]);

    Livewire::test(Dashboard::class)
        ->assertSee('Admin Dashboard')
        ->assertSee('BKG-PAID-1')
        ->assertSee('BKG-PAID-2')
        ->assertSee('BKG-PENDING-1')
        ->assertSee('BKG-CANCELLED-1')
        ->assertSee($movie1->title)
        ->assertSee($movie2->title)
        ->assertSee('2 tickets')
        ->assertSee('1 tickets')
        ->assertSee('Max: Rp 50.000');
});

it('filters bookings by search term', function () {
    $this->actingAs($this->user);

    $movie = Movie::factory()->create(['title' => 'Movie Search', 'status' => 'showing']);
    $showtime = Showtime::factory()->create([
        'movie_id' => $movie->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
    ]);

    $paidBooking1 = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime->id,
        'booking_code' => 'BKG-SEARCH-1',
        'total_price' => 10000,
        'status' => 'paid',
    ]);

    BookingSeat::create([
        'booking_id' => $paidBooking1->id,
        'seat_id' => $this->seatA1->id,
        'showtime_id' => $showtime->id,
        'status' => 'booked',
    ]);

    $paidBooking2 = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime->id,
        'booking_code' => 'BKG-SEARCH-2',
        'total_price' => 20000,
        'status' => 'paid',
    ]);

    BookingSeat::create([
        'booking_id' => $paidBooking2->id,
        'seat_id' => $this->seatA2->id,
        'showtime_id' => $showtime->id,
        'status' => 'booked',
    ]);

    Livewire::test(Dashboard::class)
        ->set('bookingSearch', 'BKG-SEARCH-2')
        ->assertSee('BKG-SEARCH-2')
        ->assertDontSee('BKG-SEARCH-1');
});

it('filters bookings by status', function () {
    $this->actingAs($this->user);

    $movie = Movie::factory()->create(['title' => 'Movie Status', 'status' => 'showing']);
    $showtime = Showtime::factory()->create([
        'movie_id' => $movie->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
    ]);

    $paid = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime->id,
        'booking_code' => 'BKG-PAID',
        'total_price' => 10000,
        'status' => 'paid',
    ]);
    BookingSeat::create([
        'booking_id' => $paid->id,
        'seat_id' => $this->seatA1->id,
        'showtime_id' => $showtime->id,
        'status' => 'booked',
    ]);

    $pending = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime->id,
        'booking_code' => 'BKG-PENDING',
        'total_price' => 15000,
        'status' => 'pending',
    ]);
    BookingSeat::create([
        'booking_id' => $pending->id,
        'seat_id' => $this->seatA1->id,
        'showtime_id' => $showtime->id,
        'status' => 'selected',
    ]);

    $cancelled = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime->id,
        'booking_code' => 'BKG-CANCELLED',
        'total_price' => 20000,
        'status' => 'cancelled',
    ]);
    BookingSeat::create([
        'booking_id' => $cancelled->id,
        'seat_id' => $this->seatA2->id,
        'showtime_id' => $showtime->id,
        'status' => 'selected',
    ]);

    Livewire::test(Dashboard::class)
        ->call('setBookingStatusFilter', 'paid')
        ->assertSee('BKG-PAID')
        ->assertDontSee('BKG-PENDING')
        ->assertDontSee('BKG-CANCELLED');
});

it('opens booking details modal with seats and payment info', function () {
    $this->actingAs($this->user);

    $movie = Movie::factory()->create(['title' => 'Movie Modal', 'status' => 'showing']);
    $showtime = Showtime::factory()->create([
        'movie_id' => $movie->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
    ]);

    $paid = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime->id,
        'booking_code' => 'BKG-MODAL',
        'total_price' => 12000,
        'status' => 'paid',
    ]);

    BookingSeat::create([
        'booking_id' => $paid->id,
        'seat_id' => $this->seatA1->id,
        'showtime_id' => $showtime->id,
        'status' => 'booked',
    ]);

    BookingSeat::create([
        'booking_id' => $paid->id,
        'seat_id' => $this->seatA2->id,
        'showtime_id' => $showtime->id,
        'status' => 'booked',
    ]);

    Payment::create([
        'booking_id' => $paid->id,
        'amount' => 12000,
        'status' => 'success',
        'payment_method' => 'bank_transfer',
    ]);

    Livewire::test(Dashboard::class)
        ->call('openBookingModal', $paid->id)
        ->assertSee('BKG-MODAL')
        ->assertSee('Success')
        ->assertSee('A1')
        ->assertSee('A2');
});
