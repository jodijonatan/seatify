<div>
    <div class="mb-8">
        <flux:button href="{{ route('movie.show', $showtime->movie_id) }}" variant="ghost" icon="arrow-left" wire:navigate class="mb-4">Back to Movie</flux:button>
        
        <div class="flex flex-col md:flex-row gap-8">
            <div class="w-full md:w-2/3">
                <flux:card>
                    <flux:heading size="xl" class="mb-6 text-center">Select Your Seats</flux:heading>
                    
                    <!-- Screen indicator -->
                    <div class="w-full max-w-md mx-auto h-8 bg-zinc-200 dark:bg-zinc-700 rounded-t-[50%] mb-12 relative flex items-center justify-center">
                        <span class="text-xs text-zinc-500 font-medium absolute -bottom-6 tracking-widest uppercase">Screen</span>
                    </div>

                    <div class="flex flex-col items-center gap-4 py-8 overflow-x-auto">
                        @foreach($groupedSeats as $row => $rowSeats)
                            <div class="flex items-center gap-2">
                                <span class="w-6 font-bold text-center text-zinc-500">{{ $row }}</span>
                                <div class="flex gap-2">
                                    @foreach($rowSeats as $seat)
                                        @php
                                            $isBooked = in_array($seat->id, $bookedSeatIds);
                                            $isSelected = in_array($seat->id, $selectedSeatIds);
                                            
                                            $btnClass = 'w-10 h-10 rounded-t-lg rounded-b-sm flex items-center justify-center text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-zinc-900 ';
                                            
                                            if ($isBooked) {
                                                $btnClass .= 'bg-zinc-300 text-zinc-500 cursor-not-allowed dark:bg-zinc-700 dark:text-zinc-500';
                                            } elseif ($isSelected) {
                                                $btnClass .= 'bg-primary text-white hover:bg-primary/90';
                                            } else {
                                                $btnClass .= 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 cursor-pointer dark:bg-emerald-900/30 dark:text-emerald-400 dark:hover:bg-emerald-800/50 outline outline-1 outline-emerald-300 dark:outline-emerald-800';
                                            }
                                        @endphp
                                        
                                        <button 
                                            type="button"
                                            wire:click="toggleSeat({{ $seat->id }})"
                                            @disabled($isBooked)
                                            class="{{ $btnClass }}"
                                            title="{{ $seat->row }}{{ $seat->number }}"
                                        >
                                            {{ $seat->number }}
                                        </button>
                                    @endforeach
                                </div>
                                <span class="w-6 font-bold text-center text-zinc-500">{{ $row }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Legend -->
                    <div class="flex justify-center gap-6 mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-emerald-100 outline outline-1 outline-emerald-300 dark:bg-emerald-900/30 dark:outline-emerald-800"></div>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">Available</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-primary"></div>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">Selected</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-zinc-300 dark:bg-zinc-700"></div>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">Booked</span>
                        </div>
                    </div>
                </flux:card>
            </div>
            
            <div class="w-full md:w-1/3">
                <flux:card class="sticky top-6">
                    <flux:heading size="lg" class="mb-4 border-b border-zinc-200 dark:border-zinc-700 pb-2">Booking Summary</flux:heading>
                    
                    <div class="space-y-4 mb-6">
                        <div>
                            <flux:text class="text-xs text-zinc-500 font-medium uppercase tracking-wide">Movie</flux:text>
                            <flux:heading size="md">{{ $showtime->movie->title }}</flux:heading>
                        </div>
                        
                        <div>
                            <flux:text class="text-xs text-zinc-500 font-medium uppercase tracking-wide">Cinema</flux:text>
                            <flux:text class="font-medium">{{ $showtime->cinema->name }}</flux:text>
                            <flux:text class="text-sm text-zinc-500">{{ $showtime->studio->name }}</flux:text>
                        </div>
                        
                        <div>
                            <flux:text class="text-xs text-zinc-500 font-medium uppercase tracking-wide">Date & Time</flux:text>
                            <flux:text class="font-medium">{{ \Carbon\Carbon::parse($showtime->show_date)->format('l, d F Y') }}</flux:text>
                            <flux:text class="font-medium text-primary">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</flux:text>
                        </div>
                    </div>
                    
                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <flux:text class="font-medium">Selected Seats</flux:text>
                            <flux:badge>{{ count($selectedSeatIds) }}</flux:badge>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            @forelse($selectedSeatIds as $selectedId)
                                @php
                                    $s = $seats->firstWhere('id', $selectedId);
                                @endphp
                                @if($s)
                                    <span class="px-2 py-1 bg-zinc-100 dark:bg-zinc-800 rounded text-sm font-medium border border-zinc-200 dark:border-zinc-700">{{ $s->row }}{{ $s->number }}</span>
                                @endif
                            @empty
                                <flux:text class="text-sm text-zinc-500 italic">No seats selected yet.</flux:text>
                            @endforelse
                        </div>
                        
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total Price</span>
                            <span class="text-primary">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if(session()->has('error'))
                        <div class="mb-4">
                            <flux:card class="bg-red-50 text-red-600 border-red-200 text-sm">
                                {{ session('error') }}
                            </flux:card>
                        </div>
                    @endif

                    <flux:button 
                        wire:click="proceedToCheckout" 
                        variant="primary" 
                        class="w-full" 
                        :disabled="empty($selectedSeatIds)"
                    >
                        Proceed to Checkout
                    </flux:button>
                </flux:card>
            </div>
        </div>
    </div>
</div>
