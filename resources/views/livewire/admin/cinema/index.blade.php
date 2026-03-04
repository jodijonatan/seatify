<div>
    <div class="mb-4 flex justify-between items-center">
        <flux:heading size="xl">{{ __('Cinemas Management') }}</flux:heading>
        <flux:button wire:click="create" variant="primary" icon="plus">Add Cinema</flux:button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4">
            <flux:card class="bg-primary/10 border-primary text-primary">
                {{ session('message') }}
            </flux:card>
        </div>
    @endif

    <div class="mb-4 flex gap-4">
        <flux:input wire:model.live.debounce.500ms="search" placeholder="Search by name or city..." icon="magnifying-glass" class="max-w-md" />
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>City</flux:table.column>
            <flux:table.column>Address</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($cinemas as $cinema)
                <flux:table.row>
                    <flux:table.cell><strong>{{ $cinema->name }}</strong></flux:table.cell>
                    <flux:table.cell>{{ $cinema->city }}</flux:cell>
                    <flux:table.cell>{{ $cinema->address }}</flux:cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                            <flux:menu>
                                <flux:menu.item wire:click="edit({{ $cinema->id }})" icon="pencil">Edit</flux:menu.item>
                                <flux:menu.item wire:click="delete({{ $cinema->id }})" icon="trash" class="text-red-500 hover:text-red-600">Delete</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4" class="text-center py-6 text-zinc-500">No cinemas found.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $cinemas->links() }}
    </div>

    <flux:modal wire:model.live="isModalOpen">
        <div class="p-6">
            <flux:heading size="lg" class="mb-4">{{ $cinemaId ? 'Edit Cinema' : 'Add Cinema' }}</flux:heading>

            <form wire:submit="store" class="space-y-4">
                <flux:input label="Name" wire:model="name" />
                <flux:input label="City" wire:model="city" />
                <flux:textarea label="Address" wire:model="address" />

                <div class="mt-6 flex justify-end gap-2">
                    <flux:button wire:click="closeModal" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Save</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
