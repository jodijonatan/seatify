<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    protected $fillable = ['name', 'address', 'city'];

    public function studios()
    {
        return $this->hasMany(Studio::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
