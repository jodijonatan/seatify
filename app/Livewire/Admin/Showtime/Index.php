<?php
namespace App\Livewire\Admin\Showtime;

use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Studio;
use App\Models\Showtime;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Form fields
    public $showtimeId;
    public $movie_id;
    public $cinema_id;
    public $studio_id;
    public $show_date;
    public $start_time;
    public $end_time;
    public $price;

    public $isModalOpen = false;

    public function render()
    {
        $showtimes = Showtime::with(['movie', 'cinema', 'studio'])
            ->whereHas('movie', function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('cinema', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $movies = Movie::where('status', 'showing')->orWhere('status', 'upcoming')->get();
        $cinemas = Cinema::all();
        $studios = $this->cinema_id ? Studio::where('cinema_id', $this->cinema_id)->get() : [];

        return view('livewire.admin.showtime.index', compact('showtimes', 'movies', 'cinemas', 'studios'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->showtimeId = null;
        $this->movie_id = '';
        $this->cinema_id = '';
        $this->studio_id = '';
        $this->show_date = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->price = '';
    }

    public function store()
    {
        $this->validate([
            'movie_id' => 'required|exists:movies,id',
            'cinema_id' => 'required|exists:cinemas,id',
            'studio_id' => 'required|exists:studios,id',
            'show_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price' => 'required|numeric|min:0',
        ]);

        Showtime::updateOrCreate(['id' => $this->showtimeId], [
            'movie_id' => $this->movie_id,
            'cinema_id' => $this->cinema_id,
            'studio_id' => $this->studio_id,
            'show_date' => $this->show_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'price' => $this->price,
        ]);

        session()->flash('message', $this->showtimeId ? 'Showtime Updated Successfully.' : 'Showtime Created Successfully.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $showtime = Showtime::findOrFail($id);
        $this->showtimeId = $id;
        $this->movie_id = $showtime->movie_id;
        $this->cinema_id = $showtime->cinema_id;
        $this->studio_id = $showtime->studio_id;
        $this->show_date = $showtime->show_date;
        $this->start_time = \Carbon\Carbon::parse($showtime->start_time)->format('H:i');
        $this->end_time = \Carbon\Carbon::parse($showtime->end_time)->format('H:i');
        $this->price = $showtime->price;
        $this->openModal();
    }

    public function delete($id)
    {
        Showtime::findOrFail($id)->delete();
        session()->flash('message', 'Showtime Deleted Successfully.');
    }
}
