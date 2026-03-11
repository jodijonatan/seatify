<?php

namespace App\Livewire\Admin\Studio;

use App\Models\Cinema;
use App\Models\Studio;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Form fields
    public $studioId;

    public $cinema_id;

    public $name;

    public $capacity;

    public $isModalOpen = false;

    public function render()
    {
        $studios = Studio::with('cinema')
            ->where('name', 'like', '%'.$this->search.'%')
            ->paginate(10);

        $cinemas = Cinema::all();

        return view('livewire.admin.studio.index', compact('studios', 'cinemas'));
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
        $this->studioId = null;
        $this->cinema_id = '';
        $this->name = '';
        $this->capacity = '';
    }

    public function store()
    {
        $this->validate([
            'cinema_id' => 'required|exists:cinemas,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        Studio::updateOrCreate(['id' => $this->studioId], [
            'cinema_id' => $this->cinema_id,
            'name' => $this->name,
            'capacity' => $this->capacity,
        ]);

        session()->flash('message', $this->studioId ? 'Studio Updated Successfully.' : 'Studio Created Successfully.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $studio = Studio::findOrFail($id);
        $this->studioId = $id;
        $this->cinema_id = $studio->cinema_id;
        $this->name = $studio->name;
        $this->capacity = $studio->capacity;
        $this->openModal();
    }

    public function delete($id)
    {
        Studio::findOrFail($id)->delete();
        session()->flash('message', 'Studio Deleted Successfully.');
    }

    public function generateSeats($id)
    {
        $studio = Studio::findOrFail($id);

        // Prevent duplicate generation if seats already exist
        if ($studio->seats()->count() > 0) {
            session()->flash('message', 'Seats already generated for this studio.');

            return;
        }

        $capacity = $studio->capacity;
        $seatsPerRow = 10;
        $rows = ceil($capacity / $seatsPerRow);
        $alphabet = range('A', 'Z');

        $seatData = [];
        $seatCount = 0;

        for ($i = 0; $i < $rows; $i++) {
            $rowLetter = $alphabet[$i];
            for ($j = 1; $j <= $seatsPerRow; $j++) {
                if ($seatCount >= $capacity) {
                    break 2;
                }

                $seatData[] = [
                    'studio_id' => $studio->id,
                    'row' => $rowLetter,
                    'number' => $j,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $seatCount++;
            }
        }

        \App\Models\Seat::insert($seatData);

        session()->flash('message', 'Seats generated successfully for '.$studio->name);
    }
}
