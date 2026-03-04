<div>
    <div class="mb-4 flex justify-between items-center">
        <flux:heading size="xl">{{ __('Bookings Management') }}</flux:heading>
    </div>

    @if (session()->has('message'))
        <div class="mb-4">
            <flux:card class="bg-primary/10 border-primary text-primary">
                {{ session('message') }}
            </flux:card>
        </div>
    @endif

    <div class="mb-4 flex gap-4">
        <flux:input wire:model.live.debounce.500ms="search" placeholder="Search by booking code or user..." icon="magnifying-glass" class="max-w-md" />
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Booking Code</flux:table.column>
            <flux:table.column>User</flux:table.column>
            <flux:table.column>Movie & Showtime</flux:table.column>
            <flux:table.column>Total Price</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($bookings as $booking)
                <flux:table.row>
                    <flux:table.cell><strong>{{ $booking->booking_code }}</strong></flux:table.cell>
                    <flux:table.cell>
                        {{ optional($booking->user)->name }} <br/>
                        <span class="text-zinc-500 text-sm">{{ optional($booking->user)->email }}</span>
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ optional(optional($booking->showtime)->movie)->title }} <br/>
                        <span class="text-xs font-semibold">{{ \Carbon\Carbon::parse(optional($booking->showtime)->show_date)->format('d M Y') }} at {{ \Carbon\Carbon::parse(optional($booking->showtime)->start_time)->format('H:i') }}</span>
                    </flux:table.cell>
                    <flux:table.cell>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="{{ $booking->status === 'paid' ? 'green' : ($booking->status === 'cancelled' ? 'red' : 'zinc') }}">
                            {{ ucfirst($booking->status) }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        @if($booking->status === 'pending')
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item wire:click="markAsPaid({{ $booking->id }})" icon="check-circle" class="text-green-500 hover:text-green-600">Mark as Paid</flux:menu.item>
                                    <flux:menu.item wire:click="cancelBooking({{ $booking->id }})" icon="x-circle" class="text-red-500 hover:text-red-600">Cancel Booking</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        @else
                            <span class="text-zinc-400 text-xs">No Actions</span>
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" class="text-center py-6 text-zinc-500">No bookings found.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>
