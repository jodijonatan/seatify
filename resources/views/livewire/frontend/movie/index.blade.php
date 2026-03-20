<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" class="font-bold">Movies Library 🎬</flux:heading>
            <flux:text class="text-zinc-500">Discover current hits and upcoming blockbusters.</flux:text>
        </div>
        
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Search movies..." class="md:w-64" />
            <flux:select wire:model.live="status" placeholder="All Status" class="md:w-48">
                <flux:select.option value="">All Status</flux:select.option>
                <flux:select.option value="showing">Now Showing</flux:select.option>
                <flux:select.option value="upcoming">Coming Soon</flux:select.option>
            </flux:select>
        </div>
    </div>

    @if($movies->count() > 0)
        <!-- Movies Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
            @foreach($movies as $movie)
                <flux:card class="flex flex-col h-full hover:shadow-xl transition-all group overflow-hidden border-zinc-200 dark:border-zinc-700">
                    <div class="relative aspect-[2/3] overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        @if($movie->poster_url)
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-zinc-400 gap-2">
                                <flux:icon name="film" class="size-10" />
                                <span class="text-xs">No Poster</span>
                            </div>
                        @endif
                        
                        <div class="absolute top-2 right-2">
                            <flux:badge color="{{ $movie->status === 'showing' ? 'green' : 'yellow' }}" size="sm" class="shadow-sm">
                                {{ $movie->status === 'showing' ? 'Now Showing' : 'Coming Soon' }}
                            </flux:badge>
                        </div>
                    </div>
                    
                    <div class="p-4 flex flex-col flex-1">
                        <flux:heading size="md" class="font-bold truncate group-hover:text-primary transition-colors">{{ $movie->title }}</flux:heading>
                        <flux:text class="text-xs text-zinc-500 mt-1 flex items-center gap-1">
                            <flux:icon name="clock" class="size-3" /> {{ $movie->duration }} mins
                        </flux:text>
                        
                        <div class="mt-auto pt-4 flex gap-2">
                             <flux:button variant="primary" class="flex-1" size="sm" href="{{ route('movie.show', $movie->id) }}" wire:navigate>
                                Ticket Info
                            </flux:button>
                        </div>
                    </div>
                </flux:card>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $movies->links() }}
        </div>
    @else
        <flux:card class="py-16 text-center">
            <flux:icon name="film" class="size-16 mx-auto text-zinc-300 mb-4" />
            <flux:heading size="lg" class="text-zinc-400">No movies found in our library.</flux:heading>
            <flux:button variant="ghost" class="mt-4" wire:click="$set('search', ''); $set('status', '');">
                Reset Filters
            </flux:button>
        </flux:card>
    @endif
</div>
