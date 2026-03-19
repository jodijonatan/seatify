<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'duration', 'poster_image', 'status'];

    protected function posterUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function () {
                if (! $this->poster_image) {
                    return null;
                }

                if (filter_var($this->poster_image, FILTER_VALIDATE_URL)) {
                    return $this->poster_image;
                }

                return \Illuminate\Support\Facades\Storage::url($this->poster_image);
            },
        );
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
