<?php
namespace App\Livewire\Admin\Cinema;

use App\Models\Cinema;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Form fields for Create/Edit
    public $cinemaId;
    public $name;
    public $address;
    public $city;

    public $isModalOpen = false;

    public function render()
    {
        $cinemas = Cinema::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('city', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.cinema.index', compact('cinemas'));
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
        $this->cinemaId = null;
        $this->name = '';
        $this->address = '';
        $this->city = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
        ]);

        Cinema::updateOrCreate(['id' => $this->cinemaId], [
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
        ]);

        session()->flash('message', $this->cinemaId ? 'Cinema Updated Successfully.' : 'Cinema Created Successfully.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $cinema = Cinema::findOrFail($id);
        $this->cinemaId = $id;
        $this->name = $cinema->name;
        $this->address = $cinema->address;
        $this->city = $cinema->city;
        $this->openModal();
    }

    public function delete($id)
    {
        Cinema::findOrFail($id)->delete();
        session()->flash('message', 'Cinema Deleted Successfully.');
    }
}
