<div>
    <div class="max-w-4xl mx-auto mb-8">
        <flux:button href="{{ route('home') }}" variant="ghost" icon="arrow-left" wire:navigate class="mb-4">Back to Home</flux:button>
        
        <div class="flex flex-col md:flex-row gap-8">
            <div class="w-full md:w-2/3">
                <flux:card>
                    <flux:heading size="xl" class="mb-6">Review Your Booking</flux:heading>
                    
                    <div class="flex flex-col sm:flex-row gap-6 mb-8 border-b border-zinc-200 dark:border-zinc-700 pb-8">
                        @if($booking->showtime->movie->poster_url)
                            <img src="{{ $booking->showtime->movie->poster_url }}" alt="{{ $booking->showtime->movie->title }}" class="w-24 md:w-32 h-auto object-cover rounded-lg shadow-md hidden sm:block">
                        @else
                            <div class="w-24 md:w-32 aspect-[2/3] bg-zinc-200 dark:bg-zinc-700 rounded-lg hidden sm:flex items-center justify-center text-zinc-500 shadow-md">
                                <flux:icon.film class="size-8" />
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <flux:heading size="lg" class="mb-1">{{ $booking->showtime->movie->title }}</flux:heading>
                            <flux:text class="text-zinc-500 mb-4">{{ $booking->showtime->cinema->name }} • {{ $booking->showtime->studio->name }}</flux:text>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <flux:text class="text-xs text-zinc-500 font-medium uppercase truncate tracking-wide">Date</flux:text>
                                    <flux:text class="font-medium">{{ \Carbon\Carbon::parse($booking->showtime->show_date)->format('l, d M Y') }}</flux:text>
                                </div>
                                <div>
                                    <flux:text class="text-xs text-zinc-500 font-medium uppercase truncate tracking-wide">Time</flux:text>
                                    <flux:text class="font-medium text-primary">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <flux:heading size="md" class="mb-4">Seats Selected</flux:heading>
                        <div class="flex flex-wrap gap-2">
                            @foreach($booking->seats as $bSeat)
                                <span class="px-3 py-1.5 bg-primary/10 text-primary rounded font-bold border border-primary/20">{{ $bSeat->seat->row }}{{ $bSeat->seat->number }}</span>
                            @endforeach
                        </div>
                    </div>
                </flux:card>
                
                <flux:card class="mt-6">
                    <flux:heading size="lg" class="mb-4">Payment Method</flux:heading>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                            <input type="radio" wire:model="paymentMethod" value="bank_transfer" class="w-4 h-4 text-primary bg-zinc-100 border-zinc-300 focus:ring-primary dark:focus:ring-primary dark:ring-offset-zinc-800 focus:ring-2 dark:bg-zinc-700 dark:border-zinc-600">
                            <span class="ml-4 font-medium">Bank Transfer</span>
                        </label>
                        
                        <label class="flex items-center p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                            <input type="radio" wire:model="paymentMethod" value="credit_card" class="w-4 h-4 text-primary bg-zinc-100 border-zinc-300 focus:ring-primary dark:focus:ring-primary dark:ring-offset-zinc-800 focus:ring-2 dark:bg-zinc-700 dark:border-zinc-600">
                            <span class="ml-4 font-medium">Credit / Debit Card</span>
                        </label>
                        
                        <label class="flex items-center p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                            <input type="radio" wire:model="paymentMethod" value="ewallet" class="w-4 h-4 text-primary bg-zinc-100 border-zinc-300 focus:ring-primary dark:focus:ring-primary dark:ring-offset-zinc-800 focus:ring-2 dark:bg-zinc-700 dark:border-zinc-600">
                            <span class="ml-4 font-medium">E-Wallet (OVO, GoPay, Dana)</span>
                        </label>
                    </div>
                </flux:card>
            </div>
            
            <div class="w-full md:w-1/3">
                <flux:card class="sticky top-6">
                    <flux:heading size="lg" class="mb-6 border-b border-zinc-200 dark:border-zinc-700 pb-2">Order Summary</flux:heading>
                    
                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex justify-between text-zinc-600 dark:text-zinc-400">
                            <span>Booking ID</span>
                            <span class="font-mono text-zinc-900 dark:text-zinc-100">{{ $booking->booking_code }}</span>
                        </div>
                        <div class="flex justify-between text-zinc-600 dark:text-zinc-400">
                            <span>Ticket Price</span>
                            <span class="text-zinc-900 dark:text-zinc-100">Rp {{ number_format($booking->showtime->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-zinc-600 dark:text-zinc-400">
                            <span>Quantity</span>
                            <span class="text-zinc-900 dark:text-zinc-100">{{ $booking->seats->count() }}x</span>
                        </div>
                        <div class="flex justify-between text-zinc-600 dark:text-zinc-400">
                            <span>Service Fee</span>
                            <span class="text-zinc-900 dark:text-zinc-100">Free</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 mb-8">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total</span>
                            <span class="text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if(session()->has('error'))
                        <div class="mb-4">
                            <flux:card class="bg-red-50 text-red-600 border-red-200 text-sm">
                                {{ session('error') }}
                            </flux:card>
                        </div>
                    @endif

                    <div class="space-y-3">
                        <flux:button wire:click="processPayment" variant="primary" class="w-full">
                            Pay Now
                        </flux:button>
                        <flux:button wire:click="cancelBooking" variant="ghost" class="w-full text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">
                            Cancel Booking
                        </flux:button>
                    </div>
                </flux:card>
            </div>
        </div>
    </div>
</div>
