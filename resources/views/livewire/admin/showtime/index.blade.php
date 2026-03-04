<div>
    <div class="mb-4 flex justify-between items-center">
        <flux:heading size="xl">{{ __('Showtimes Management') }}</flux:heading>
        <flux:button wire:click="create" variant="primary" icon="plus">Add Showtime</flux:button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4">
            <flux:card class="bg-primary/10 border-primary text-primary">
                {{ session('message') }}
            </flux:card>
        </div>
    @endif

    <div class="mb-4 flex gap-4">
        <flux:input wire:model.live.debounce.500ms="search" placeholder="Search movie or cinema..." icon="magnifying-glass" class="max-w-md" />
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Movie</flux:table.column>
            <flux:table.column>Cinema & Studio</flux:table.column>
            <flux:table.column>Date & Time</flux:table.column>
            <flux:table.column>Price</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($showtimes as $showtime)
                <flux:table.row>
                    <flux:table.cell><strong>{{ optional($showtime->movie)->title }}</strong></flux:table.cell>
                    <flux:table.cell>
                        {{ optional($showtime->cinema)->name }} <br/>
                        <span class="text-zinc-500 text-sm">{{ optional($showtime->studio)->name }}</span>
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ \Carbon\Carbon::parse($showtime->show_date)->format('d M Y') }} <br/>
                        <span class="text-xs font-semibold">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}</span>
                    </flux:table.cell>
                    <flux:table.cell>Rp {{ number_format($showtime->price, 0, ',', '.') }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                            <flux:menu>
                                <flux:menu.item wire:click="edit({{ $showtime->id }})" icon="pencil">Edit</flux:menu.item>
                                <flux:menu.item wire:click="delete({{ $showtime->id }})" icon="trash" class="text-red-500 hover:text-red-600">Delete</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5" class="text-center py-6 text-zinc-500">No showtimes found.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $showtimes->links() }}
    </div>

    <flux:modal wire:model.live="isModalOpen">
        <div class="p-6">
            <flux:heading size="lg" class="mb-4">{{ $showtimeId ? 'Edit Showtime' : 'Add Showtime' }}</flux:heading>

            <form wire:submit="store" class="space-y-4">
                <flux:select label="Movie" wire:model="movie_id">
                    <option value="">Select Movie</option>
                    @foreach($movies as $movie)
                        <option value="{{ $movie->id }}">{{ $movie->title }} ({{ $movie->duration }}m)</option>
                    @endforeach
                </flux:select>
                
                <flux:select label="Cinema" wire:model.live="cinema_id">
                    <option value="">Select Cinema</option>
                    @foreach($cinemas as $cinema)
                        <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select label="Studio" wire:model="studio_id">
                    <option value="">Select Studio</option>
                    @foreach($studios as $studio)
                        <option value="{{ $studio->id }}">{{ $studio->name }}</option>
                    @endforeach
                </flux:select>

                <div class="grid grid-cols-2 gap-4">
                    <flux:input label="Show Date" type="date" wire:model="show_date" />
                    <flux:input label="Price" type="number" wire:model="price" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <flux:input label="Start Time" type="time" wire:model="start_time" />
                    <flux:input label="End Time" type="time" wire:model="end_time" />
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <flux:button wire:click="closeModal" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Save</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
