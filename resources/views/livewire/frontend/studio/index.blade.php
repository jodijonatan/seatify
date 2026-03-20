<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" class="font-bold">Our Studios & Auditoriums 🎭</flux:heading>
            <flux:text class="text-zinc-500">Explore our premium screens and seating capacities.</flux:text>
        </div>
        
        <div class="w-full md:w-auto">
            <flux:select wire:model.live="cinemaId" placeholder="Filter by Cinema" class="md:w-64">
                <flux:select.option value="">All Cinemas</flux:select.option>
                @foreach($allCinemas as $cinema)
                    <flux:select.option value="{{ $cinema->id }}">{{ $cinema->name }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
    </div>

    @foreach($cinemas as $cinema)
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-zinc-200 dark:border-zinc-700 pb-3">
                <flux:heading size="lg" class="font-bold text-primary">{{ $cinema->name }}</flux:heading>
                <flux:badge size="sm" color="zinc">{{ $cinema->studios->count() }} Screen(s)</flux:badge>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($cinema->studios as $studio)
                    <flux:card class="p-6 h-full flex flex-col hover:shadow-md transition-all border-zinc-200 dark:border-zinc-700 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-zinc-100 dark:bg-zinc-800 rounded-xl group-hover:bg-primary/10 transition-colors">
                                <flux:icon.presentation-chart-bar class="size-6 text-zinc-500 group-hover:text-primary transition-colors" />
                            </div>
                        </div>

                        <flux:heading size="md" class="font-bold mb-2">{{ $studio->name }}</flux:heading>
                        
                        <div class="space-y-2 mt-auto">
                            <flux:text class="text-sm text-zinc-500 flex items-center gap-2">
                                <flux:icon.user-group class="size-4" /> Capacity: {{ $studio->capacity }} Seats
                            </flux:text>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        </div>
    @endforeach

    @if($cinemas->isEmpty())
        <flux:card class="py-16 text-center">
            <flux:icon.presentation-chart-bar class="size-16 mx-auto text-zinc-300 mb-4" />
            <flux:heading size="lg" class="text-zinc-400">No studios found.</flux:heading>
        </flux:card>
    @endif
</div>
