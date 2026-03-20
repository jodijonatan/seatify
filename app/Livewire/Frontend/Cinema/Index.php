<?php

namespace App\Livewire\Frontend\Cinema;

use App\Models\Cinema;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $city = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCity()
    {
        $this->resetPage();
    }

    public function render()
    {
        $cinemas = Cinema::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%');
            })
            ->when($this->city, function ($query) {
                $query->where('city', $this->city);
            })
            ->withCount('studios')
            ->paginate(12);

        $cities = Cinema::distinct()->pluck('city')->filter()->values();

        return view('livewire.frontend.cinema.index', [
            'cinemas' => $cinemas,
            'cities' => $cities,
        ]);
    }
}
