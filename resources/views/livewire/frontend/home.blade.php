<div>
    <div class="mb-8">
        <flux:heading size="xl" class="mb-6">{{ __('Now Showing') }}</flux:heading>
        
        @if($movies->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($movies as $movie)
                    <flux:card class="flex flex-col h-full hover:shadow-lg transition-shadow">
                        @if($movie->poster_url)
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-64 object-cover rounded-t-lg mb-4">
                        @else
                            <div class="w-full h-64 bg-zinc-200 dark:bg-zinc-700 rounded-t-lg mb-4 flex items-center justify-center text-zinc-500">
                                <flux:icon.film class="size-12" />
                            </div>
                        @endif
                        
                        <div class="flex-1 flex flex-col">
                            <flux:heading size="lg" class="mb-2">{{ $movie->title }}</flux:heading>
                            <flux:text class="text-sm text-zinc-500 mb-4">{{ $movie->duration }} mins</flux:text>
                            
                            <div class="mt-auto pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <flux:button href="{{ route('movie.show', $movie->id) }}" variant="primary" class="w-full" wire:navigate>Get Tickets</flux:button>
                            </div>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        @else
            <flux:card class="text-center py-12">
                <flux:icon.film class="size-12 mx-auto text-zinc-400 mb-4" />
                <flux:heading size="lg" class="text-zinc-500">No movies currently showing.</flux:heading>
            </flux:card>
        @endif
    </div>
    
    @if($upcomingMovies->count() > 0)
        <div class="mt-12">
            <flux:heading size="xl" class="mb-6">{{ __('Coming Soon') }}</flux:heading>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($upcomingMovies as $movie)
                    <div class="group relative overflow-hidden rounded-xl">
                        <div class="w-full h-48 bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                            <flux:icon.film class="size-8 text-zinc-400" />
                        </div>
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <flux:heading size="md" class="text-white text-center px-4">{{ $movie->title }}</flux:heading>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($cinemas->count() > 0)
        <div class="mt-16 mb-8">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="xl">{{ __('Our Cinemas') }}</flux:heading>
                <flux:button href="#" variant="subtle" size="sm" wire:navigate>{{ __('View All') }}</flux:button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cinemas as $cinema)
                    <flux:card class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-orange-100 text-orange-600 rounded-lg dark:bg-orange-900/30 dark:text-orange-500">
                                <flux:icon.building-office-2 class="size-6" />
                            </div>
                            <div>
                                <flux:heading size="lg" class="mb-1">{{ $cinema->name }}</flux:heading>
                                <flux:text class="text-sm text-zinc-500 mb-2">{{ $cinema->city }}</flux:text>
                                <flux:text class="text-xs text-zinc-400 line-clamp-2 leading-relaxed">{{ $cinema->address }}</flux:text>
                            </div>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        </div>
    @endif
</div>
