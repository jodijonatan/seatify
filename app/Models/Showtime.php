<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    protected $fillable = [
        'movie_id', 'cinema_id', 'studio_id', 'show_date', 'start_time', 'end_time', 'price'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
    }
}
