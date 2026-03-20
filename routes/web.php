<<<<<<< HEAD
=======
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', App\Livewire\Frontend\Home::class)->name('home');
Route::get('/movie/{id}', App\Livewire\Frontend\Movie\Show::class)->name('movie.show');

Route::middleware(['auth', 'verified'])->group(function () {
    // User Booking Routes
    Route::get('/booking/showtime/{showtimeId}', App\Livewire\Frontend\Booking\SeatSelection::class)->name('booking.seat-selection');
    Route::get('/booking/checkout/{bookingId}', App\Livewire\Frontend\Booking\Checkout::class)->name('booking.checkout');
    Route::get('/my-bookings', App\Livewire\Frontend\Booking\History::class)->name('booking.history');
    // Dashboard Redirector
    Route::get('dashboard', function () {
        if (auth()->user()->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    })->name('dashboard');

    // Admin Routes
    Route::middleware([\Spatie\Permission\Middleware\RoleMiddleware::class.':Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/cinemas', App\Livewire\Admin\Cinema\Index::class)->name('cinemas.index');
        Route::get('/studios', App\Livewire\Admin\Studio\Index::class)->name('studios.index');
        Route::get('/movies', App\Livewire\Admin\Movie\Index::class)->name('movies.index');
        Route::get('/showtimes', App\Livewire\Admin\Showtime\Index::class)->name('showtimes.index');
        Route::get('/bookings', App\Livewire\Admin\Booking\Index::class)->name('bookings.index');
        // Add other CRUD routes later
    });
});

require __DIR__.'/settings.php';
>>>>>>> parent of 40fa991 (feat: implement frontend movie, cinema, and studio listings with Livewire components and dedicated views.)
