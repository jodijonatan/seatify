<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id', 'showtime_id', 'booking_code', 'total_price', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
