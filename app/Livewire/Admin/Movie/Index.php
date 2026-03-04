<?php
namespace App\Livewire\Admin\Movie;

use App\Models\Movie;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Form fields
    public $movieId;
    public $title;
    public $description;
    public $duration;
    public $status = 'showing';

    public $isModalOpen = false;

    public function render()
    {
        $movies = Movie::where('title', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.movie.index', compact('movies'));
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
        $this->movieId = null;
        $this->title = '';
        $this->description = '';
        $this->duration = '';
        $this->status = 'showing';
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:showing,upcoming,ended',
        ]);

        Movie::updateOrCreate(['id' => $this->movieId], [
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'status' => $this->status,
        ]);

        session()->flash('message', $this->movieId ? 'Movie Updated Successfully.' : 'Movie Created Successfully.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        $this->movieId = $id;
        $this->title = $movie->title;
        $this->description = $movie->description;
        $this->duration = $movie->duration;
        $this->status = $movie->status;
        $this->openModal();
    }

    public function delete($id)
    {
        Movie::findOrFail($id)->delete();
        session()->flash('message', 'Movie Deleted Successfully.');
    }
}
