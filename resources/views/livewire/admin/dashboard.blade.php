<div>
    <div class="mb-6 flex justify-between items-center">
        <flux:heading size="xl">{{ __('Dashboard Overview') }}</flux:heading>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Revenue -->
        <flux:card>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-100 text-green-600 rounded-lg dark:bg-green-900/30 dark:text-green-500">
                    <flux:icon.banknotes class="size-6" />
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Total Revenue</flux:text>
                    <flux:heading size="lg">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</flux:heading>
                </div>
            </div>
        </flux:card>

        <!-- Total Bookings -->
        <flux:card>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg dark:bg-blue-900/30 dark:text-blue-500">
                    <flux:icon.ticket class="size-6" />
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Successful Bookings</flux:text>
                    <flux:heading size="lg">{{ number_format($totalBookings) }}</flux:heading>
                </div>
            </div>
        </flux:card>

        <!-- Total Movies Showing -->
        <flux:card>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-purple-100 text-purple-600 rounded-lg dark:bg-purple-900/30 dark:text-purple-500">
                    <flux:icon.film class="size-6" />
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Movies Showing</flux:text>
                    <flux:heading size="lg">{{ number_format($totalMovies) }}</flux:heading>
                </div>
            </div>
        </flux:card>

        <!-- Total Cinemas -->
        <flux:card>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-orange-100 text-orange-600 rounded-lg dark:bg-orange-900/30 dark:text-orange-500">
                    <flux:icon.building-office-2 class="size-6" />
                </div>
                <div>
                    <flux:text class="text-sm font-medium text-zinc-500">Total Cinemas</flux:text>
                    <flux:heading size="lg">{{ number_format($totalCinemas) }}</flux:heading>
                </div>
            </div>
        </flux:card>
    </div>

    <!-- Recent Bookings Table -->
    <flux:card>
        <flux:heading size="lg" class="mb-4">Recent Bookings</flux:heading>
        
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Code</flux:table.column>
                <flux:table.column>User</flux:table.column>
                <flux:table.column>Movie</flux:table.column>
                <flux:table.column>Price</flux:table.column>
                <flux:table.column>Status</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($recentBookings as $booking)
                    <flux:table.row>
                        <flux:table.cell><strong>{{ $booking->booking_code }}</strong></flux:table.cell>
                        <flux:table.cell>{{ optional($booking->user)->name }}</flux:table.cell>
                        <flux:table.cell>{{ optional(optional($booking->showtime)->movie)->title }}</flux:table.cell>
                        <flux:table.cell>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</flux:cell>
                        <flux:table.cell>
                            <flux:badge color="{{ $booking->status === 'paid' ? 'green' : ($booking->status === 'cancelled' ? 'red' : 'zinc') }}">
                                {{ ucfirst($booking->status) }}
                            </flux:badge>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center py-4 text-zinc-500">No recent bookings.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

        <div class="mt-4 text-right">
            <flux:button href="{{ route('admin.bookings.index') }}" variant="ghost" icon-trailing="arrow-right">View All Bookings</flux:button>
        </div>
    </flux:card>
</div>
