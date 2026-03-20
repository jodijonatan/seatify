<?php

use App\Livewire\Frontend\Booking\History;
use App\Livewire\Frontend\Booking\SeatSelection;
use App\Livewire\Frontend\Home;
use App\Livewire\Frontend\Movie\Show as MovieShow;
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
        'name' => 'Studio 1',
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

it('shows user dashboard and next booking on home page', function () {
    $this->actingAs($this->user);

    $movie = Movie::factory()->create([
        'title' => 'Next Movie',
        'status' => 'showing',
    ]);

    $showtime = Showtime::factory()->create([
        'movie_id' => $movie->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
        'show_date' => Carbon::now()->addDay()->format('Y-m-d'),
        'start_time' => '18:00',
        'end_time' => '20:00',
        'price' => 50000,
    ]);

    $booking = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime->id,
        'booking_code' => 'BKG-NEXT',
        'total_price' => 100000,
        'status' => 'paid',
    ]);

    BookingSeat::create([
        'booking_id' => $booking->id,
        'seat_id' => $this->seatA1->id,
        'showtime_id' => $showtime->id,
        'status' => 'booked',
    ]);
    BookingSeat::create([
        'booking_id' => $booking->id,
        'seat_id' => $this->seatA2->id,
        'showtime_id' => $showtime->id,
        'status' => 'booked',
    ]);

    // Payment not required for home dashboard, but keep realistic flow
    Payment::create([
        'booking_id' => $booking->id,
        'amount' => 100000,
        'status' => 'success',
        'payment_method' => 'bank_transfer',
    ]);

    Livewire::test(Home::class)
        ->assertSee('Your Dashboard')
        ->assertSee('Next Movie')
        ->assertSee('Paid')
        ->assertSee('View Ticket')
        ->assertSee('2') // seats_count
        ->assertSee('18:00'); // start_time formatting
});

it('filters booking history by search term', function () {
    $this->actingAs($this->user);

    $movie = Movie::factory()->create([
        'title' => 'History Movie',
        'status' => 'showing',
    ]);

    $showtime = Showtime::factory()->create([
        'movie_id' => $movie->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
        'show_date' => Carbon::now()->addDay()->format('Y-m-d'),
        'start_time' => '19:00',
        'end_time' => '21:00',
        'price' => 40000,
    ]);

    $paid1 = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime->id,
        'booking_code' => 'BKG-FILTER-1',
        'total_price' => 40000,
        'status' => 'paid',
    ]);
    BookingSeat::create([
        'booking_id' => $paid1->id,
        'seat_id' => $this->seatA1->id,
        'showtime_id' => $showtime->id,
        'status' => 'booked',
    ]);

    $paid2 = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime->id,
        'booking_code' => 'BKG-FILTER-2',
        'total_price' => 80000,
        'status' => 'paid',
    ]);
    BookingSeat::create([
        'booking_id' => $paid2->id,
        'seat_id' => $this->seatA2->id,
        'showtime_id' => $showtime->id,
        'status' => 'booked',
    ]);

    Livewire::test(History::class)
        ->set('bookingSearch', 'BKG-FILTER-2')
        ->assertSee('BKG-FILTER-2')
        ->assertDontSee('BKG-FILTER-1');
});

it('filters booking history by status', function () {
    $this->actingAs($this->user);

    $movie = Movie::factory()->create([
        'title' => 'Status Movie',
        'status' => 'showing',
    ]);

    $showtime = Showtime::factory()->create([
        'movie_id' => $movie->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
        'show_date' => Carbon::now()->addDay()->format('Y-m-d'),
        'start_time' => '19:00',
        'end_time' => '21:00',
        'price' => 40000,
    ]);

    $paid = Booking::create([
        'user_id' => $this->user->id,
        'showtime_id' => $showtime->id,
        'booking_code' => 'BKG-STATUS-PAID',
        'total_price' => 40000,
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
        'booking_code' => 'BKG-STATUS-PENDING',
        'total_price' => 80000,
        'status' => 'pending',
    ]);
    BookingSeat::create([
        'booking_id' => $pending->id,
        'seat_id' => $this->seatA2->id,
        'showtime_id' => $showtime->id,
        'status' => 'selected',
    ]);

    Livewire::test(History::class)
        ->call('setStatusFilter', 'paid')
        ->assertSee('BKG-STATUS-PAID')
        ->assertDontSee('BKG-STATUS-PENDING');
});

it('shows movie page with next available showtime CTA', function () {
    $movie = Movie::factory()->create([
        'title' => 'Movie Next CTA',
        'status' => 'showing',
    ]);

    Showtime::factory()->create([
        'movie_id' => $movie->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
        'show_date' => Carbon::now()->format('Y-m-d'),
        'start_time' => '17:00',
        'end_time' => '19:00',
        'price' => 30000,
    ]);

    $nextShowtime = Showtime::factory()->create([
        'movie_id' => $movie->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
        'show_date' => Carbon::now()->addDay()->format('Y-m-d'),
        'start_time' => '18:30',
        'end_time' => '20:30',
        'price' => 40000,
    ]);

    Livewire::test(MovieShow::class, ['id' => $movie->id])
        ->assertSee('Next Available Showtime')
        ->assertSee('Book Seats')
        ->assertSee(Carbon::parse($nextShowtime->start_time)->format('H:i'));
});

it('shows seat selection progress based on selected seats', function () {
    $this->actingAs($this->user);

    $movie = Movie::factory()->create([
        'title' => 'Seat Progress',
        'status' => 'showing',
    ]);

    $showtime = Showtime::factory()->create([
        'movie_id' => $movie->id,
        'cinema_id' => $this->cinema->id,
        'studio_id' => $this->studio->id,
        'show_date' => Carbon::now()->addDay()->format('Y-m-d'),
        'start_time' => '19:00',
        'end_time' => '21:00',
        'price' => 40000,
    ]);

    Livewire::test(SeatSelection::class, ['showtimeId' => $showtime->id])
        ->assertSee('0/6')
        ->call('toggleSeat', $this->seatA1->id)
        ->assertSee('1/6')
        ->call('toggleSeat', $this->seatA2->id)
        ->assertSee('2/6');
});
