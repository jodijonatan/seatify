<div class="space-y-16">
    @auth
        @if($dashboard)
            <section aria-label="User dashboard">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <flux:heading size="xl" class="font-bold tracking-tight">Your Dashboard</flux:heading>
                        <flux:text class="text-zinc-500">Ringkasan tiket dan booking terbaru untuk akun kamu.</flux:text>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <flux:button href="{{ route('booking.history') }}" variant="primary" icon-trailing="arrow-right" wire:navigate>
                            My Bookings
                        </flux:button>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-3 lg:grid-cols-4">
                    <flux:card class="rounded-2xl bg-gradient-to-br from-emerald-500/10 via-white to-transparent dark:from-emerald-500/10 dark:via-zinc-900">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-emerald-100 text-emerald-700 rounded-xl dark:bg-emerald-900/30 dark:text-emerald-400">
                                <flux:icon.ticket class="size-6" />
                            </div>
                            <div>
                                <flux:text class="text-sm font-medium text-zinc-500">Paid Tickets</flux:text>
                                <flux:heading size="lg">{{ number_format($dashboard['paidCount']) }}</flux:heading>
                            </div>
                        </div>
                    </flux:card>

                    <flux:card class="rounded-2xl bg-gradient-to-br from-amber-500/10 via-white to-transparent dark:from-amber-500/10 dark:via-zinc-900">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-amber-100 text-amber-700 rounded-xl dark:bg-amber-900/30 dark:text-amber-400">
                                <flux:icon.ticket class="size-6" />
                            </div>
                            <div>
                                <flux:text class="text-sm font-medium text-zinc-500">Pending</flux:text>
                                <flux:heading size="lg">{{ number_format($dashboard['pendingCount']) }}</flux:heading>
                            </div>
                        </div>
                    </flux:card>

                    <flux:card class="rounded-2xl bg-gradient-to-br from-rose-500/10 via-white to-transparent dark:from-rose-500/10 dark:via-zinc-900">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-rose-100 text-rose-700 rounded-xl dark:bg-rose-900/30 dark:text-rose-400">
                                <flux:icon.ticket class="size-6" />
                            </div>
                            <div>
                                <flux:text class="text-sm font-medium text-zinc-500">Cancelled</flux:text>
                                <flux:heading size="lg">{{ number_format($dashboard['cancelledCount']) }}</flux:heading>
                            </div>
                        </div>
                    </flux:card>

                    <flux:card class="rounded-2xl lg:col-span-1 bg-white/50 dark:bg-zinc-900/30">
                        @if($dashboard['nextBooking'])
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <flux:text class="text-xs text-zinc-500 font-medium uppercase tracking-wide">Next Booking</flux:text>
                                    <flux:heading size="md" class="mt-1 truncate">{{ $dashboard['nextBooking']->showtime->movie->title }}</flux:heading>
                                </div>
                                <flux:badge color="{{ $dashboard['nextBooking']->status === 'paid' ? 'green' : ($dashboard['nextBooking']->status === 'pending' ? 'amber' : 'zinc') }}">
                                    {{ ucfirst($dashboard['nextBooking']->status) }}
                                </flux:badge>
                            </div>

                            <div class="mt-3 space-y-2 text-sm">
                                <div class="flex justify-between gap-4">
                                    <span class="text-zinc-500">Cinema</span>
                                    <span class="font-medium">{{ $dashboard['nextBooking']->showtime->cinema->name }}</span>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span class="text-zinc-500">Studio</span>
                                    <span class="font-medium">{{ $dashboard['nextBooking']->showtime->studio->name }}</span>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span class="text-zinc-500">When</span>
                                    <span class="font-medium">
                                        {{ \Carbon\Carbon::parse($dashboard['nextBooking']->showtime->show_date)->format('d M Y') }},
                                        {{ \Carbon\Carbon::parse($dashboard['nextBooking']->showtime->start_time)->format('H:i') }}
                                    </span>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span class="text-zinc-500">Seats</span>
                                    <span class="font-semibold">{{ $dashboard['nextBooking']->seats_count }}</span>
                                </div>
                            </div>

                            <div class="mt-4">
                                @if($dashboard['nextBooking']->status === 'pending')
                                    <flux:button href="{{ route('booking.checkout', $dashboard['nextBooking']->id) }}" variant="primary" class="w-full" wire:navigate>
                                        Complete Payment
                                    </flux:button>
                                @else
                                    <flux:button href="{{ route('booking.history') }}" variant="subtle" class="w-full" wire:navigate>
                                        View Ticket
                                    </flux:button>
                                @endif
                            </div>
                        @else
                            <div class="py-8 text-center">
                                <flux:icon.ticket class="size-10 mx-auto text-zinc-300 dark:text-zinc-600 mb-3" />
                                <flux:heading size="md" class="text-zinc-600 dark:text-zinc-400 mb-1">No upcoming booking</flux:heading>
                                <flux:text class="text-zinc-500">Kamu belum punya booking untuk jadwal setelah hari ini.</flux:text>
                            </div>
                        @endif
                    </flux:card>
                </div>
            </section>
        @endif
    @endauth

    {{-- Section: Hero / Now Showing --}}
    <section id="now-showing">
        <div class="flex items-end justify-between mb-8">
            <div>
                <flux:heading size="xl" class="font-bold tracking-tight">{{ __('Now Showing') }}</flux:heading>
                <flux:text class="text-zinc-500">Catch the latest blockbusters in theaters now.</flux:text>
            </div>
            <flux:button href="#now-showing" variant="subtle" size="sm" class="hidden md:flex">
                See all movies
            </flux:button>
        </div>
        
        @if($movies->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($movies as $movie)
                    <div class="group relative flex flex-col">
                        {{-- Poster Container --}}
                        <div class="relative aspect-[2/3] overflow-hidden rounded-2xl bg-zinc-900 shadow-md transition-all duration-300 group-hover:-translate-y-2 group-hover:shadow-2xl">
                            @if($movie->poster_url)
                                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-zinc-700">
                                    <flux:icon.film class="size-16" />
                                </div>
                            @endif

                            {{-- Gradient Overlay & Button on Hover --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                                <flux:button href="{{ route('movie.show', $movie->id) }}" variant="primary" class="w-full shadow-lg shadow-orange-500/20" wire:navigate>
                                    Get Tickets
                                </flux:button>
                            </div>

                            {{-- Badge Duration --}}
                            <div class="absolute top-3 right-3">
                                <span class="px-2 py-1 text-[10px] font-bold bg-black/50 backdrop-blur-md text-white rounded-md border border-white/10">
                                    {{ $movie->duration }} MIN
                                </span>
                            </div>
                        </div>
                        
                        {{-- Info --}}
                        <div class="mt-4">
                            <flux:heading size="lg" class="leading-tight truncate group-hover:text-orange-500 transition-colors">{{ $movie->title }}</flux:heading>
                            <flux:text class="text-xs uppercase tracking-widest text-zinc-500 mt-1 font-medium">Action • Sci-Fi</flux:text> {{-- Contoh statis, bisa diganti genre --}}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-zinc-50 dark:bg-zinc-900/50 border border-dashed border-zinc-200 dark:border-zinc-800 rounded-3xl py-20 text-center">
                <flux:icon.film class="size-12 mx-auto text-zinc-400 mb-4" />
                <flux:heading size="lg" class="text-zinc-400">No movies currently showing.</flux:heading>
            </div>
        @endif
    </section>

    {{-- Section: Coming Soon (Horizontal Scroll on Mobile) --}}
    @if($upcomingMovies->count() > 0)
        <section class="bg-zinc-50 dark:bg-white/[0.02] -mx-4 px-4 py-12 sm:mx-0 sm:px-0 sm:rounded-3xl">
            <div class="sm:px-8">
                <flux:heading size="xl" class="mb-8 flex items-center gap-2">
                    <span class="size-2 bg-orange-500 rounded-full animate-pulse"></span>
                    {{ __('Coming Soon') }}
                </flux:heading>
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($upcomingMovies as $movie)
                        <div class="group cursor-pointer">
                            <div class="relative aspect-video mb-3 overflow-hidden rounded-xl bg-zinc-200 dark:bg-zinc-800">
                                <div class="absolute inset-0 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                     <flux:icon.film class="size-8 text-zinc-400" />
                                </div>
                                <div class="absolute inset-0 bg-orange-600/10 group-hover:bg-transparent transition-colors"></div>
                            </div>
                            <flux:heading size="sm" class="truncate font-semibold">{{ $movie->title }}</flux:heading>
                            <flux:text class="text-xs text-zinc-500 italic">Releasing soon</flux:text>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Section: Cinemas (Modern Grid) --}}
    @if($cinemas->count() > 0)
        <section id="our-cinemas">
            <div class="flex items-center justify-between mb-8">
                <flux:heading size="xl">{{ __('Our Cinemas') }}</flux:heading>
                <flux:button href="#our-cinemas" variant="subtle" icon-trailing="arrow-right" size="sm">
                    {{ __('Explore All') }}
                </flux:button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($cinemas as $cinema)
                    <flux:card class="relative overflow-hidden group hover:border-orange-500/50 transition-colors p-0">
                        <div class="p-6 flex items-start gap-4">
                            <div class="flex-shrink-0 size-12 flex items-center justify-center bg-orange-500/10 text-orange-600 rounded-xl">
                                <flux:icon.building-office-2 class="size-6" />
                            </div>
                            <div class="min-w-0">
                                <flux:heading size="lg" class="truncate">{{ $cinema->name }}</flux:heading>
                                <flux:text class="text-sm font-medium text-orange-500 mb-2">{{ $cinema->city }}</flux:text>
                                <flux:text class="text-xs text-zinc-400 line-clamp-1">{{ $cinema->address }}</flux:text>
                            </div>
                        </div>
                        {{-- Subtle background decoration --}}
                        <div class="absolute -right-4 -bottom-4 size-24 bg-zinc-100 dark:bg-zinc-800/50 rounded-full scale-0 group-hover:scale-100 transition-transform duration-500 -z-10"></div>
                    </flux:card>
                @endforeach
            </div>
        </section>
    @endif
</div>