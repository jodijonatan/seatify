<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Studio;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key constraints for truncation
        \Schema::disableForeignKeyConstraints();
        \App\Models\Showtime::truncate();
        \App\Models\BookingSeat::truncate();
        \App\Models\Booking::truncate();
        \App\Models\Seat::truncate();
        \App\Models\Studio::truncate();
        \App\Models\Cinema::truncate();
        \App\Models\Movie::truncate();
        \Schema::enableForeignKeyConstraints();

        // 1. Create Movies with high-quality posters
        $movies = [
            Movie::create([
                'title' => 'Spider-Man: Beyond the Spider-Verse',
                'description' => 'Miles Morales returns for the next chapter of the Spider-Verse saga, an epic adventure that will transport Brooklyn\'s full-time, friendly neighborhood Spider-Man across the Multiverse to join forces with Gwen Stacy and a new team of Spider-People.',
                'duration' => 140,
                'status' => 'showing',
                'poster_image' => 'https://image.tmdb.org/t/p/w500/8Vt6mWEReuy4Of61Lnj5Xj704m8.jpg',
            ]),
            Movie::create([
                'title' => 'Dune: Part Two',
                'description' => 'Paul Atreides unites with Chani and the Fremen while on a warpath of revenge against the conspirators who destroyed his family.',
                'duration' => 166,
                'status' => 'showing',
                'poster_image' => 'https://image.tmdb.org/t/p/w500/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg',
            ]),
            Movie::create([
                'title' => 'Oppenheimer',
                'description' => 'The story of American scientist, J. Robert Oppenheimer, and his role in the development of the atomic bomb.',
                'duration' => 180,
                'status' => 'showing',
                'poster_image' => 'https://image.tmdb.org/t/p/w500/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg',
            ]),
            Movie::create([
                'title' => 'Mission: Impossible - Dead Reckoning',
                'description' => 'Ethan Hunt and his IMF team embark on their most dangerous mission yet.',
                'duration' => 163,
                'status' => 'showing',
                'poster_image' => 'https://image.tmdb.org/t/p/w500/NNxYkU70HPurnNCSiCjYAmacwm.jpg',
            ]),
            Movie::create([
                'title' => 'Deadpool & Wolverine',
                'description' => 'Deadpool crosses paths with Wolverine in an action-packed, multiverse-spanning adventure.',
                'duration' => 127,
                'status' => 'showing',
                'poster_image' => 'https://image.tmdb.org/t/p/w500/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg',
            ]),
        ];

        // 2. Create Cinemas
        $cinemas = [
            Cinema::create([
                'name' => 'Grand Indonesia CGV',
                'address' => 'Jl. M.H. Thamrin No.1',
                'city' => 'Jakarta',
            ]),
            Cinema::create([
                'name' => 'Senayan City XXI',
                'address' => 'Jl. Asia Afrika Lot. 19',
                'city' => 'Jakarta',
            ]),
            Cinema::create([
                'name' => 'Pakuwon Mall Premiere',
                'address' => 'Jl. Puncak Indah Lontar No. 2',
                'city' => 'Surabaya',
            ]),
        ];

        // 3. Create Studios and Seats
        $studios = [];
        foreach ($cinemas as $cinema) {
            // Give each cinema 3 studios
            for ($i = 1; $i <= 3; $i++) {
                $studio = Studio::create([
                    'cinema_id' => $cinema->id,
                    'name' => 'Studio '.$i,
                    'capacity' => 50, // 5 rows (A-E) x 10 seats
                ]);
                $studios[] = $studio;

                // Create seats for this studio
                $rows = ['A', 'B', 'C', 'D', 'E'];
                foreach ($rows as $row) {
                    for ($number = 1; $number <= 10; $number++) {
                        Seat::create([
                            'studio_id' => $studio->id,
                            'row' => $row,
                            'number' => $number,
                        ]);
                    }
                }
            }
        }

        // 4. Create Showtimes
        // Only for "showing" movies
        $showingMovies = array_filter($movies, fn ($m) => $m->status === 'showing');

        $times = ['10:00:00', '13:00:00', '16:00:00', '19:00:00'];

        foreach ($showingMovies as $movie) {
            foreach ($studios as $index => $studio) {
                // Not every movie plays in every studio. Pseudo-randomly assign:
                if (rand(0, 10) > 7) {
                    continue;
                }

                // Generate showtimes for the next 365 days
                for ($day = 0; $day < 365; $day++) {
                    $date = Carbon::now()->addDays($day)->format('Y-m-d');

                    // Pick 2 random times for this movie in this studio on this day
                    $selectedTimes = (array) array_rand(array_flip($times), 2);

                    foreach ($selectedTimes as $time) {
                        Showtime::create([
                            'movie_id' => $movie->id,
                            'cinema_id' => $studio->cinema_id,
                            'studio_id' => $studio->id,
                            'show_date' => $date,
                            'start_time' => $time,
                            'end_time' => Carbon::parse($time)->addMinutes($movie->duration)->format('H:i:s'),
                            'price' => rand(4, 7) * 10000, // 40k to 70k
                        ]);
                    }
                }
            }
        }
    }
}
