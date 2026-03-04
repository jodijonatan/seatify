<div>
    <div class="mb-4 flex justify-between items-center">
        <flux:heading size="xl">{{ __('Movies Management') }}</flux:heading>
        <flux:button wire:click="create" variant="primary" icon="plus">Add Movie</flux:button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4">
            <flux:card class="bg-primary/10 border-primary text-primary">
                {{ session('message') }}
            </flux:card>
        </div>
    @endif

    <div class="mb-4 flex gap-4">
        <flux:input wire:model.live.debounce.500ms="search" placeholder="Search by title..." icon="magnifying-glass" class="max-w-md" />
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Title</flux:table.column>
            <flux:table.column>Duration</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($movies as $movie)
                <flux:table.row>
                    <flux:table.cell><strong>{{ $movie->title }}</strong></flux:table.cell>
                    <flux:table.cell>{{ $movie->duration }} mins</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="{{ $movie->status === 'showing' ? 'green' : ($movie->status === 'upcoming' ? 'blue' : 'zinc') }}">
                            {{ ucfirst($movie->status) }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                            <flux:menu>
                                <flux:menu.item wire:click="edit({{ $movie->id }})" icon="pencil">Edit</flux:menu.item>
                                <flux:menu.item wire:click="delete({{ $movie->id }})" icon="trash" class="text-red-500 hover:text-red-600">Delete</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4" class="text-center py-6 text-zinc-500">No movies found.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $movies->links() }}
    </div>

    <flux:modal wire:model.live="isModalOpen">
        <div class="p-6">
            <flux:heading size="lg" class="mb-4">{{ $movieId ? 'Edit Movie' : 'Add Movie' }}</flux:heading>

            <form wire:submit="store" class="space-y-4">
                <flux:input label="Title" wire:model="title" />
                <flux:textarea label="Description" wire:model="description" />
                <flux:input label="Duration (Minutes)" type="number" wire:model="duration" />
                <flux:select label="Status" wire:model="status">
                    <option value="showing">Showing</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="ended">Ended</option>
                </flux:select>

                <div class="mt-6 flex justify-end gap-2">
                    <flux:button wire:click="closeModal" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Save</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
