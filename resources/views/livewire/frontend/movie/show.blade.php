<div>
    <div class="mb-8 flex items-start gap-8 flex-col md:flex-row">
        <div class="w-full md:w-1/3">
            @if($movie->poster_url)
                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-auto object-cover rounded-xl shadow-lg">
            @else
                <div class="w-full aspect-[2/3] bg-zinc-200 dark:bg-zinc-700 rounded-xl flex items-center justify-center text-zinc-500 shadow-lg">
                    <flux:icon.film class="size-20 text-zinc-400" />
                </div>
            @endif
        </div>
        
        <div class="w-full md:w-2/3 flex flex-col justify-start">
            <flux:heading size="3xl" class="mb-2 font-bold">{{ $movie->title }}</flux:heading>
            
            <div class="flex items-center gap-3 mb-6">
                <flux:badge color="{{ $movie->status === 'showing' ? 'green' : 'zinc' }}">{{ ucfirst($movie->status) }}</flux:badge>
                <flux:text class="text-zinc-500 flex items-center gap-1">
                    <flux:icon.clock class="size-4" /> {{ $movie->duration }} mins
                </flux:text>
            </div>
            
            <div class="mb-8">
                <flux:heading size="lg" class="mb-2">Synopsis</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $movie->description ?? 'No synopsis available.' }}</flux:text>
            </div>

            @if($movie->status === 'showing')
                <div>
                    <flux:heading size="xl" class="mb-6 border-b border-zinc-200 dark:border-zinc-700 pb-2">Available Showtimes</flux:heading>
                    
                    @forelse($groupedShowtimes as $cinemaName => $dates)
                        <div class="mb-6">
                            <flux:heading size="lg" class="mb-4 text-primary">{{ $cinemaName }}</flux:heading>
                            
                            <div class="space-y-4">
                                @foreach($dates as $date => $showtimes)
                                    <div>
                                        <flux:text class="font-medium mb-2">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</flux:text>
                                        <div class="flex flex-wrap gap-3">
                                            @foreach($showtimes as $showtime)
                                                <flux:button href="{{ route('booking.seat-selection', $showtime->id) }}" variant="subtle" wire:navigate class="flex flex-col items-center !h-auto py-2 px-4 hover:border-primary">
                                                    <span class="font-bold text-lg leading-tight">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</span>
                                                    <span class="text-xs text-zinc-500">{{ $showtime->studio->name }}</span>
                                                    <span class="text-xs text-green-600 dark:text-green-500 font-medium">Rp {{ number_format($showtime->price, 0, ',', '.') }}</span>
                                                </flux:button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <flux:card class="text-center py-6">
                            <flux:text class="text-zinc-500">No showtimes currently scheduled for this movie.</flux:text>
                        </flux:card>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</div>
