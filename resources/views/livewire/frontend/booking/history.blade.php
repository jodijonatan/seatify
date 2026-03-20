<div>
    <div class="max-w-4xl mx-auto mb-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <flux:heading size="xl">My Bookings</flux:heading>
                <flux:text class="text-zinc-500">Cari bookingmu atau filter berdasarkan status.</flux:text>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                <flux:input
                    wire:model.live.debounce.500ms="bookingSearch"
                    placeholder="Search booking code, movie, cinema..."
                    icon="magnifying-glass"
                    class="max-w-md"
                />

                <flux:dropdown>
                    <flux:button variant="ghost" size="sm" class="min-w-40" icon-trailing="chevron-down">
                        Status: {{ $statusFilter === 'all' ? 'All' : ucfirst($statusFilter) }}
                    </flux:button>
                    <flux:menu>
                        <flux:menu.item wire:click="setStatusFilter('all')">All</flux:menu.item>
                        <flux:menu.item wire:click="setStatusFilter('paid')">Paid</flux:menu.item>
                        <flux:menu.item wire:click="setStatusFilter('pending')">Pending</flux:menu.item>
                        <flux:menu.item wire:click="setStatusFilter('cancelled')">Cancelled</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3 mb-8">
            <flux:card class="rounded-2xl bg-zinc-50 dark:bg-zinc-900/30">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-100 text-emerald-700 rounded-xl dark:bg-emerald-900/30 dark:text-emerald-400">
                        <flux:icon.ticket class="size-6" />
                    </div>
                    <div>
                        <flux:text class="text-sm font-medium text-zinc-500">Paid</flux:text>
                        <flux:heading size="lg">{{ number_format($paidCount) }}</flux:heading>
                    </div>
                </div>
            </flux:card>

            <flux:card class="rounded-2xl bg-zinc-50 dark:bg-zinc-900/30">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-amber-100 text-amber-700 rounded-xl dark:bg-amber-900/30 dark:text-amber-400">
                        <flux:icon.ticket class="size-6" />
                    </div>
                    <div>
                        <flux:text class="text-sm font-medium text-zinc-500">Pending</flux:text>
                        <flux:heading size="lg">{{ number_format($pendingCount) }}</flux:heading>
                    </div>
                </div>
            </flux:card>

            <flux:card class="rounded-2xl bg-zinc-50 dark:bg-zinc-900/30">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-rose-100 text-rose-700 rounded-xl dark:bg-rose-900/30 dark:text-rose-400">
                        <flux:icon.ticket class="size-6" />
                    </div>
                    <div>
                        <flux:text class="text-sm font-medium text-zinc-500">Cancelled</flux:text>
                        <flux:heading size="lg">{{ number_format($cancelledCount) }}</flux:heading>
                    </div>
                </div>
            </flux:card>
        </div>

        @if(session()->has('message'))
            <div class="mb-6">
                <flux:card class="bg-green-50 text-green-700 border-green-200">
                    {{ session('message') }}
                </flux:card>
            </div>
        @endif

        <div class="space-y-6">
            @forelse($bookings as $booking)
                <flux:card class="{{ $booking->id == $highlight ? 'ring-2 ring-primary ring-offset-2 dark:ring-offset-zinc-900' : '' }}">
                    <div class="flex flex-col md:flex-row gap-6">
                        @if($booking->showtime->movie->poster_url)
                            <img src="{{ $booking->showtime->movie->poster_url }}" alt="{{ $booking->showtime->movie->title }}" class="w-24 md:w-32 h-auto object-cover rounded-lg shadow-sm hidden sm:block">
                        @else
                            <div class="w-24 md:w-32 aspect-[2/3] bg-zinc-200 dark:bg-zinc-700 rounded-lg hidden sm:flex items-center justify-center text-zinc-500 shadow-sm">
                                <flux:icon.film class="size-8" />
                            </div>
                        @endif
                        
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <flux:heading size="lg">{{ $booking->showtime->movie->title }}</flux:heading>
                                    <flux:badge color="{{ $booking->status === 'paid' ? 'green' : ($booking->status === 'cancelled' ? 'red' : 'zinc') }}">
                                        {{ ucfirst($booking->status) }}
                                    </flux:badge>
                                </div>
                                <flux:text class="text-zinc-500 mb-4">{{ $booking->showtime->cinema->name }} • {{ $booking->showtime->studio->name }}</flux:text>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4 bg-zinc-50 dark:bg-zinc-800/50 p-3 rounded-lg border border-zinc-100 dark:border-zinc-800">
                                    <div>
                                        <flux:text class="text-xs text-zinc-500 font-medium uppercase truncate tracking-wide">Date & Time</flux:text>
                                        <flux:text class="text-sm font-medium">{{ \Carbon\Carbon::parse($booking->showtime->show_date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}</flux:text>
                                    </div>
                                    <div>
                                        <flux:text class="text-xs text-zinc-500 font-medium uppercase truncate tracking-wide">Booking Code</flux:text>
                                        <flux:text class="text-sm font-mono font-bold">{{ $booking->booking_code }}</flux:text>
                                    </div>
                                    <div class="col-span-2">
                                        <flux:text class="text-xs text-zinc-500 font-medium uppercase truncate tracking-wide mb-1">Seats</flux:text>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($booking->seats as $bSeat)
                                                <span class="px-2 py-0.5 bg-zinc-200 dark:bg-zinc-700 rounded text-xs font-semibold">{{ $bSeat->seat->row }}{{ $bSeat->seat->number }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center border-t border-zinc-100 dark:border-zinc-800 pt-4 mt-auto">
                                <flux:text class="font-medium">Total: <span class="text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span></flux:text>
                                
                                @if($booking->status === 'paid')
                                    <!-- Simple E-ticket trigger (e.g., printing or zooming) -->
                                    <flux:button variant="ghost" size="sm" icon="qr-code" class="text-primary" onclick="window.print()">Print E-Ticket</flux:button>
                                @elseif($booking->status === 'pending')
                                    <flux:button href="{{ route('booking.checkout', $booking->id) }}" variant="primary" size="sm" wire:navigate>Pay Now</flux:button>
                                @endif
                            </div>
                        </div>
                    </div>
                </flux:card>
            @empty
                <flux:card class="text-center py-12">
                    <flux:icon.ticket class="size-16 mx-auto text-zinc-300 dark:text-zinc-600 mb-4" />
                    <flux:heading size="lg" class="text-zinc-600 dark:text-zinc-400 mb-2">No bookings found</flux:heading>
                    <flux:text class="text-zinc-500 mb-6">
                        Tidak ada booking yang cocok dengan pencarian atau filter yang dipilih.
                    </flux:text>
                    <flux:button href="{{ route('home') }}" variant="primary" wire:navigate>Browse Movies</flux:button>
                </flux:card>
            @endforelse
            
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
