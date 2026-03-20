<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="font-bold">Welcome back, {{ auth()->user()->name }}! 👋</flux:heading>
            <flux:text class="text-zinc-500">Here's what's happening with your movie tickets.</flux:text>
        </div>
        <flux:button href="{{ route('home') }}" icon="ticket" variant="primary">Book New Ticket</flux:button>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <flux:card class="flex flex-col gap-2 p-6">
            <flux:text class="text-zinc-500 font-medium">Total Bookings</flux:text>
            <div class="flex items-center justify-between">
                <flux:heading size="2xl" class="font-bold">{{ $stats['total_bookings'] }}</flux:heading>
                <div class="p-3 bg-primary/10 rounded-xl">
                    <flux:icon.ticket class="size-6 text-primary" />
                </div>
            </div>
        </flux:card>

        <flux:card class="flex flex-col gap-2 p-6">
            <flux:text class="text-zinc-500 font-medium">Pending Payments</flux:text>
            <div class="flex items-center justify-between">
                <flux:heading size="2xl" class="font-bold text-yellow-600 dark:text-yellow-500">{{ $stats['pending_bookings'] }}</flux:heading>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl">
                    <flux:icon.credit-card class="size-6 text-yellow-600 dark:text-yellow-500" />
                </div>
            </div>
        </flux:card>

        <flux:card class="flex flex-col gap-2 p-6">
            <flux:text class="text-zinc-500 font-medium">Confirmed Bookings</flux:text>
            <div class="flex items-center justify-between">
                <flux:heading size="2xl" class="font-bold text-green-600 dark:text-green-500">{{ $stats['completed_bookings'] }}</flux:heading>
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                    <flux:icon.check-circle class="size-6 text-green-600 dark:text-green-500" />
                </div>
            </div>
        </flux:card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Bookings -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-4">
                <flux:heading size="lg" class="font-bold">Recent Bookings</flux:heading>
                <flux:button variant="subtle" size="sm" href="{{ route('booking.history') }}">View All</flux:button>
            </div>

            @if($recentBookings->count() > 0)
                <div class="space-y-4">
                    @foreach($recentBookings as $booking)
                        <flux:card class="p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
                            <div class="size-16 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if($booking->showtime->movie->poster_url)
                                    <img src="{{ $booking->showtime->movie->poster_url }}" alt="{{ $booking->showtime->movie->title }}" class="w-full h-full object-cover">
                                @else
                                    <flux:icon.film class="size-8 text-zinc-400" />
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <flux:heading size="md" class="truncate font-bold">{{ $booking->showtime->movie->title }}</flux:heading>
                                <flux:text class="text-sm text-zinc-500 truncate">
                                    {{ $booking->showtime->cinema->name }} • {{ $booking->showtime->studio->name }}
                                </flux:text>
                                <flux:text class="text-xs text-zinc-400 mt-1">
                                    {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('D, d M Y • H:i') }}
                                </flux:text>
                            </div>

                            <div class="text-right flex-shrink-0">
                                <flux:badge color="{{ $booking->status === 'paid' ? 'green' : ($booking->status === 'pending' ? 'yellow' : 'zinc') }}">
                                    {{ ucfirst($booking->status) }}
                                </flux:badge>
                                <div class="mt-1 font-bold">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </div>
                            </div>
                        </flux:card>
                    @endforeach
                </div>
            @else
                <flux:card class="py-12 text-center">
                    <flux:icon.ticket class="size-12 mx-auto text-zinc-300 mb-4" />
                    <flux:text class="text-zinc-500">You haven't made any bookings yet.</flux:text>
                    <div class="mt-4">
                        <flux:button variant="subtle" href="{{ route('home') }}">Explore Movies</flux:button>
                    </div>
                </flux:card>
            @endif
        </div>

        <!-- Recommended Movies -->
        <div class="space-y-6">
            <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4">
                <flux:heading size="lg" class="font-bold">Recommended</flux:heading>
            </div>

            <div class="grid grid-cols-1 gap-4">
                @foreach($recommendedMovies as $movie)
                    <a href="{{ route('movie.show', $movie->id) }}" class="group block">
                        <flux:card class="p-3 flex items-center gap-3 group-hover:border-primary transition-colors">
                            <div class="size-20 rounded-lg bg-zinc-100 dark:bg-zinc-800 overflow-hidden flex-shrink-0">
                                @if($movie->poster_url)
                                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                                @else
                                    <flux:icon.film class="size-8 text-zinc-400 m-auto mt-6" />
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <flux:heading size="sm" class="truncate font-bold group-hover:text-primary transition-colors">{{ $movie->title }}</flux:heading>
                                <flux:text class="text-xs text-zinc-500 block mt-1">{{ $movie->duration }} mins</flux:text>
                                <flux:badge size="sm" class="mt-2" color="green">Now Showing</flux:badge>
                            </div>
                        </flux:card>
                    </a>
                @endforeach
            </div>
            
            <flux:button variant="ghost" class="w-full" href="{{ route('movies.index') }}">View All Movies</flux:button>
        </div>
    </div>
</div>
