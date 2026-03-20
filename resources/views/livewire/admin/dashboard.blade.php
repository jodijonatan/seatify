<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <flux:heading size="xl" class="font-bold tracking-tight">{{ __('Admin Dashboard') }}</flux:heading>
            <flux:text class="text-zinc-500">KPI ringkas, trend pendapatan, dan aktivitas booking terbaru.</flux:text>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
            <flux:input
                wire:model.live.debounce.500ms="bookingSearch"
                placeholder="Search booking code, user, or movie..."
                icon="magnifying-glass"
                class="max-w-md"
            />

            <flux:dropdown>
                <flux:button variant="ghost" size="sm" class="min-w-40" icon-trailing="chevron-down">
                    Status: {{ $bookingStatusFilter === 'all' ? 'All' : ucfirst($bookingStatusFilter) }}
                </flux:button>
                <flux:menu>
                    <flux:menu.item wire:click="setBookingStatusFilter('all')">All</flux:menu.item>
                    <flux:menu.item wire:click="setBookingStatusFilter('paid')">Paid</flux:menu.item>
                    <flux:menu.item wire:click="setBookingStatusFilter('pending')">Pending</flux:menu.item>
                    <flux:menu.item wire:click="setBookingStatusFilter('cancelled')">Cancelled</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-5">
        <!-- Revenue Paid -->
        <flux:card class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500/10 via-white to-transparent dark:from-emerald-500/10 dark:via-zinc-900">
            <div class="relative z-10 flex items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-100 text-emerald-700 rounded-xl dark:bg-emerald-900/30 dark:text-emerald-400">
                        <flux:icon.banknotes class="size-6" />
                    </div>
                    <div>
                        <flux:text class="text-sm font-medium text-zinc-500">Revenue (Paid)</flux:text>
                        <flux:heading size="lg">Rp {{ number_format($totalRevenuePaid, 0, ',', '.') }}</flux:heading>
                    </div>
                </div>
            </div>

            <div class="relative z-10 mt-4">
                <div class="flex justify-between items-center text-xs text-zinc-500">
                    <span>Conversion</span>
                    <span>{{ round($paidRate * 100) }}%</span>
                </div>
                <div class="mt-2 h-2 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                    <div class="h-2 bg-emerald-500 rounded-full" style="width: {{ round($paidRate * 100) }}%"></div>
                </div>
            </div>
        </flux:card>

        <!-- Paid -->
        <flux:card class="rounded-2xl bg-gradient-to-br from-emerald-500/10 via-white to-transparent dark:from-emerald-500/10 dark:via-zinc-900">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-emerald-100 text-emerald-700 rounded-xl dark:bg-emerald-900/30 dark:text-emerald-400">
                    <flux:icon.ticket class="size-6" />
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Paid Bookings</flux:text>
                    <flux:heading size="lg">{{ number_format($paidBookingsCount) }}</flux:heading>
                </div>
            </div>
        </flux:card>

        <!-- Pending -->
        <flux:card class="rounded-2xl bg-gradient-to-br from-amber-500/10 via-white to-transparent dark:from-amber-500/10 dark:via-zinc-900">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-amber-100 text-amber-700 rounded-xl dark:bg-amber-900/30 dark:text-amber-400">
                    <flux:icon.ticket class="size-6" />
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Pending</flux:text>
                    <flux:heading size="lg">{{ number_format($pendingBookingsCount) }}</flux:heading>
                </div>
            </div>
        </flux:card>

        <!-- Cancelled -->
        <flux:card class="rounded-2xl bg-gradient-to-br from-rose-500/10 via-white to-transparent dark:from-rose-500/10 dark:via-zinc-900">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-rose-100 text-rose-700 rounded-xl dark:bg-rose-900/30 dark:text-rose-400">
                    <flux:icon.ticket class="size-6" />
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Cancelled</flux:text>
                    <flux:heading size="lg">{{ number_format($cancelledBookingsCount) }}</flux:heading>
                </div>
            </div>
        </flux:card>

        <!-- Movies Showing -->
        <flux:card class="rounded-2xl bg-gradient-to-br from-orange-500/10 via-white to-transparent dark:from-orange-500/10 dark:via-zinc-900">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-orange-100 text-orange-700 rounded-xl dark:bg-orange-900/30 dark:text-orange-400">
                    <flux:icon.film class="size-6" />
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Movies Showing</flux:text>
                    <flux:heading size="lg">{{ number_format($totalMoviesShowing) }}</flux:heading>
                </div>
            </div>
        </flux:card>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Revenue Trend -->
        <flux:card class="rounded-3xl lg:col-span-2">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <flux:heading size="lg">Revenue Trend (Last 7 Days)</flux:heading>
                    <flux:text class="text-sm text-zinc-500">Berdasarkan `created_at` booking dengan status `paid`.</flux:text>
                </div>
                <div class="text-right">
                    <flux:text class="text-sm text-zinc-500">Max: Rp {{ number_format($revenueMax, 0, ',', '.') }}</flux:text>
                </div>
            </div>

            <div class="mt-6 flex items-end gap-3 h-28">
                @foreach($revenueTrend as $point)
                    <div class="flex-1 flex flex-col items-center justify-end gap-2">
                        <div class="w-full bg-zinc-100 dark:bg-zinc-900 rounded-lg overflow-hidden">
                            <div class="bg-emerald-500 rounded-lg" style="height: {{ max(6, $point['heightPct']) }}%"></div>
                        </div>
                        <div class="text-[10px] text-zinc-500 truncate">{{ $point['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </flux:card>

        <!-- Top Movies -->
        <flux:card class="rounded-3xl">
            <flux:heading size="lg">Top Movies</flux:heading>
            <flux:text class="text-sm text-zinc-500">Tiket terjual (paid booking + booked seats).</flux:text>

            <div class="mt-5 space-y-4">
                @forelse($topMovies as $movie)
                    @php
                        $tickets = (int) $movie->tickets_sold;
                        $sharePct = $topTicketsMax > 0 ? (($tickets / $topTicketsMax) * 100) : 0;
                    @endphp
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <flux:text class="font-semibold truncate">{{ $movie->title }}</flux:text>
                            </div>
                            <flux:text class="text-sm text-zinc-500 whitespace-nowrap">{{ number_format($tickets) }} tickets</flux:text>
                        </div>
                        <div class="h-2 bg-zinc-100 dark:bg-zinc-900 rounded-full overflow-hidden">
                            <div class="h-2 bg-primary rounded-full" style="width: {{ $sharePct }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-zinc-500">
                        <flux:text>No ticket sales yet.</flux:text>
                    </div>
                @endforelse
            </div>
        </flux:card>
    </div>

    <!-- Bookings Activity -->
    <flux:card class="rounded-3xl">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <flux:heading size="lg">Bookings Activity</flux:heading>
                <flux:text class="text-sm text-zinc-500">Menampilkan maksimal 10 hasil sesuai filter.</flux:text>
            </div>

            <div class="flex items-center gap-2">
                <flux:button href="{{ route('admin.bookings.index') }}" variant="ghost" icon-trailing="arrow-right">
                    View All
                </flux:button>
            </div>
        </div>

        <div class="mt-4">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Code</flux:table.column>
                    <flux:table.column>User</flux:table.column>
                    <flux:table.column>Movie</flux:table.column>
                    <flux:table.column>Tickets</flux:table.column>
                    <flux:table.column>Price</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Action</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($recentBookings as $booking)
                        <flux:table.row>
                            <flux:table.cell>
                                <button
                                    type="button"
                                    wire:click="openBookingModal({{ $booking->id }})"
                                    class="text-left font-semibold text-primary hover:underline"
                                >
                                    {{ $booking->booking_code }}
                                </button>
                            </flux:table.cell>

                            <flux:table.cell>{{ optional($booking->user)->name }}</flux:table.cell>

                            <flux:table.cell>{{ optional(optional($booking->showtime)->movie)->title }}</flux:table.cell>

                            <flux:table.cell>{{ number_format($booking->seats_count) }}</flux:table.cell>

                            <flux:table.cell>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</flux:table.cell>

                            <flux:table.cell>
                                <flux:badge color="{{ $booking->status === 'paid' ? 'green' : ($booking->status === 'cancelled' ? 'red' : 'zinc') }}">
                                    {{ ucfirst($booking->status) }}
                                </flux:badge>
                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:button wire:click="openBookingModal({{ $booking->id }})" variant="ghost" size="sm">
                                    Details
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="7" class="text-center py-6 text-zinc-500">
                                No bookings found for the selected filter.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </flux:card>

    <!-- Booking Details Modal -->
    <flux:modal wire:model.live="isBookingModalOpen">
        <div class="p-6">
            @if($selectedBooking)
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <flux:heading size="lg" class="truncate">Booking {{ $selectedBooking->booking_code }}</flux:heading>
                        <flux:text class="text-sm text-zinc-500">
                            {{ optional($selectedBooking->user)->name }} • {{ optional(optional($selectedBooking->showtime)->movie)->title }}
                        </flux:text>
                    </div>
                    <flux:button wire:click="closeBookingModal" variant="ghost" size="sm">Close</flux:button>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <flux:card class="rounded-2xl bg-zinc-50 dark:bg-zinc-900/30">
                        <flux:heading size="sm">Summary</flux:heading>
                        <div class="mt-3 space-y-2 text-sm">
                            <div class="flex justify-between gap-4">
                                <span class="text-zinc-500">Status</span>
                                <flux:badge color="{{ $selectedBooking->status === 'paid' ? 'green' : ($selectedBooking->status === 'cancelled' ? 'red' : 'zinc') }}">
                                    {{ ucfirst($selectedBooking->status) }}
                                </flux:badge>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-zinc-500">Tickets</span>
                                <span class="font-semibold">{{ $selectedBooking->seats->count() }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-zinc-500">Total</span>
                                <span class="font-semibold text-primary">Rp {{ number_format($selectedBooking->total_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-zinc-500">Created</span>
                                <span class="font-mono">{{ $selectedBooking->created_at?->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </flux:card>

                    <flux:card class="rounded-2xl bg-zinc-50 dark:bg-zinc-900/30">
                        <flux:heading size="sm">Showtime</flux:heading>
                        <div class="mt-3 space-y-2 text-sm">
                            <div class="flex justify-between gap-4">
                                <span class="text-zinc-500">Cinema</span>
                                <span class="font-semibold">{{ optional(optional($selectedBooking->showtime)->cinema)->name }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-zinc-500">Studio</span>
                                <span class="font-semibold">{{ optional(optional($selectedBooking->showtime)->studio)->name }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-zinc-500">Date</span>
                                <span class="font-semibold">{{ optional($selectedBooking->showtime)->show_date }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-zinc-500">Time</span>
                                <span class="font-semibold">
                                    {{ optional($selectedBooking->showtime)->start_time }} - {{ optional($selectedBooking->showtime)->end_time }}
                                </span>
                            </div>
                        </div>
                    </flux:card>
                </div>

                <flux:card class="mt-4 rounded-2xl bg-zinc-50 dark:bg-zinc-900/30">
                    <flux:heading size="sm">Seats</flux:heading>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @forelse($selectedBooking->seats as $bSeat)
                            <span class="px-2 py-1 bg-zinc-100 dark:bg-zinc-800 rounded text-sm font-semibold border border-zinc-200/60 dark:border-zinc-700">
                                {{ $bSeat->seat->row }}{{ $bSeat->seat->number }}
                            </span>
                        @empty
                            <flux:text class="text-sm text-zinc-500">No seat data.</flux:text>
                        @endforelse
                    </div>
                </flux:card>

                <flux:card class="mt-4 rounded-2xl bg-zinc-50 dark:bg-zinc-900/30">
                    <flux:heading size="sm">Payment</flux:heading>
                    <div class="mt-3 space-y-2 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-zinc-500">Payment Status</span>
                            @if($selectedBooking->payment)
                                <flux:badge color="{{ $selectedBooking->payment->status === 'success' ? 'green' : ($selectedBooking->payment->status === 'failed' ? 'red' : 'zinc') }}">
                                    {{ ucfirst($selectedBooking->payment->status) }}
                                </flux:badge>
                            @else
                                <flux:badge color="zinc">N/A</flux:badge>
                            @endif
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="text-zinc-500">Method</span>
                            <span class="font-semibold">{{ $selectedBooking->payment?->payment_method ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="text-zinc-500">Amount</span>
                            <span class="font-semibold text-primary">Rp {{ number_format($selectedBooking->payment?->amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </flux:card>
            @else
                <div class="py-10 text-center">
                    <flux:text class="text-zinc-500">Loading booking details...</flux:text>
                </div>
            @endif
        </div>
    </flux:modal>
</div>
