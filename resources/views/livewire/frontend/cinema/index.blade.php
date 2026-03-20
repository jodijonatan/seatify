<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" class="font-bold">Our Cinemas 🍿</flux:heading>
            <flux:text class="text-zinc-500">Find the nearest cinema and enjoy your favorite movies.</flux:text>
        </div>
        
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Search cinemas..." class="md:w-64" />
            <flux:select wire:model.live="city" placeholder="All Cities" class="md:w-48">
                <flux:select.option value="">All Cities</flux:select.option>
                @foreach($cities as $cityName)
                    <flux:select.option value="{{ $cityName }}">{{ $cityName }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
    </div>

    <!-- Cinemas Grid -->
    @if($cinemas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cinemas as $cinema)
                <flux:card class="p-6 h-full flex flex-col hover:shadow-lg transition-all group border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-zinc-100 dark:bg-zinc-800 rounded-xl group-hover:bg-primary/10 transition-colors">
                            <flux:icon name="building-office-2" class="size-6 text-zinc-500 group-hover:text-primary transition-colors" />
                        </div>
                        <flux:badge color="zinc">{{ $cinema->studios_count }} Studios</flux:badge>
                    </div>

                    <flux:heading size="lg" class="font-bold mb-2 group-hover:text-primary transition-colors">{{ $cinema->name }}</flux:heading>
                    
                    <div class="space-y-3 mb-6 flex-grow">
                        <div class="flex gap-2">
                            <flux:icon name="map-pin" class="size-4 text-zinc-400 mt-1 flex-shrink-0" />
                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 capitalize">
                                {{ $cinema->address }}{{ $cinema->city ? ', ' . $cinema->city : '' }}
                            </flux:text>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        <flux:button variant="subtle" class="w-full" href="{{ route('home') }}?cinema={{ $cinema->id }}">
                            Browse Showtimes
                        </flux:button>
                    </div>
                </flux:card>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $cinemas->links() }}
        </div>
    @else
        <flux:card class="py-16 text-center">
            <flux:icon name="building-office-2" class="size-16 mx-auto text-zinc-300 mb-4" />
            <flux:heading size="lg" class="text-zinc-400">No cinemas found matching your criteria.</flux:heading>
            <flux:button variant="ghost" class="mt-4" wire:click="$set('search', ''); $set('city', '');">
                Clear Filters
            </flux:button>
        </flux:card>
    @endif
</div>
