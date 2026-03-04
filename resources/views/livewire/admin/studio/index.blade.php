<div>
    <div class="mb-4 flex justify-between items-center">
        <flux:heading size="xl">{{ __('Studios Management') }}</flux:heading>
        <flux:button wire:click="create" variant="primary" icon="plus">Add Studio</flux:button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4">
            <flux:card class="bg-primary/10 border-primary text-primary">
                {{ session('message') }}
            </flux:card>
        </div>
    @endif

    <div class="mb-4 flex gap-4">
        <flux:input wire:model.live.debounce.500ms="search" placeholder="Search by name..." icon="magnifying-glass" class="max-w-md" />
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Cinema</flux:table.column>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Capacity (Seats)</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($studios as $studio)
                <flux:table.row>
                    <flux:table.cell>{{ $studio->cinema->name }}</flux:table.cell>
                    <flux:table.cell><strong>{{ $studio->name }}</strong></flux:table.cell>
                    <flux:table.cell>{{ $studio->capacity }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                            <flux:menu>
                                <flux:menu.item wire:click="generateSeats({{ $studio->id }})" icon="layout-grid">Generate Seats</flux:menu.item>
                                <flux:menu.item wire:click="edit({{ $studio->id }})" icon="pencil">Edit</flux:menu.item>
                                <flux:menu.item wire:click="delete({{ $studio->id }})" icon="trash" class="text-red-500 hover:text-red-600">Delete</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4" class="text-center py-6 text-zinc-500">No studios found.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $studios->links() }}
    </div>

    <flux:modal wire:model.live="isModalOpen">
        <div class="p-6">
            <flux:heading size="lg" class="mb-4">{{ $studioId ? 'Edit Studio' : 'Add Studio' }}</flux:heading>

            <form wire:submit="store" class="space-y-4">
                <flux:select label="Cinema" wire:model="cinema_id">
                    <option value="">Select Cinema</option>
                    @foreach($cinemas as $cinema)
                        <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                    @endforeach
                </flux:select>
                <flux:input label="Name" wire:model="name" />
                <flux:input label="Capacity" type="number" wire:model="capacity" />

                <div class="mt-6 flex justify-end gap-2">
                    <flux:button wire:click="closeModal" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Save</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
