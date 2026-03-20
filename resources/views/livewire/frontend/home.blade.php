<div class="space-y-20 pb-20">
    {{-- Section: User Dashboard --}}
    @auth
        @if($dashboard)
            <section aria-label="User dashboard" class="relative">
                {{-- Decorative Background Blur --}}
                <div class="absolute -top-24 -left-24 size-96 bg-orange-500/10 blur-[120px] rounded-full -z-10"></div>
                
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-8">
                    <div>
                        <flux:heading size="2xl" class="font-extrabold tracking-tight italic uppercase">Halo, {{ Auth::user()->name }}! 👋</flux:heading>
                        <flux:text class="text-zinc-500 text-lg">Ini adalah ringkasan aktivitas nontonmu.</flux:text>
                    </div>

                    <div class="flex gap-3">
                        <flux:button href="{{ route('booking.history') }}" variant="filled" class="bg-zinc-900 dark:bg-white dark:text-zinc-900 hover:scale-105 transition-transform" icon-trailing="arrow-right" wire:navigate>
                            Riwayat Pesanan
                        </flux:button>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-4">
                    {{-- Stats Cards --}}
                    @php
                        $stats = [
                            ['label' => 'Paid Tickets', 'count' => $dashboard['paidCount'], 'color' => 'emerald', 'icon' => 'ticket'],
                            ['label' => 'Pending', 'count' => $dashboard['pendingCount'], 'color' => 'amber', 'icon' => 'clock'],
                            ['label' => 'Cancelled', 'count' => $dashboard['cancelledCount'], 'color' => 'rose', 'icon' => 'x-circle'],
                        ];
                    @endphp

                    @foreach($stats as $stat)
                        <flux:card class="relative overflow-hidden border-none bg-white/50 dark:bg-zinc-900/50 backdrop-blur-xl shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-800">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400 rounded-2xl">
                                    <flux:icon name="{{ $stat['icon'] }}" class="size-6" />
                                </div>
                                <div>
                                    <flux:text class="text-xs font-bold uppercase tracking-wider text-zinc-500">{{ $stat['label'] }}</flux:text>
                                    <flux:heading size="xl" class="font-black">{{ number_format($stat['count']) }}</flux:heading>
                                </div>
                            </div>
                        </flux:card>
                    @endforeach

                    {{-- Next Booking Featured Card --}}
                    <flux:card class="md:col-span-1 border-none bg-orange-600 text-white shadow-lg shadow-orange-500/20 relative overflow-hidden group">
                        <div class="absolute right-[-20px] top-[-20px] opacity-10 group-hover:scale-110 transition-transform duration-700">
                            <flux:icon.ticket class="size-32 rotate-12" />
                        </div>
                        
                        @if($dashboard['nextBooking'])
                            <div class="relative z-10">
                                <flux:text class="text-orange-200 text-[10px] font-bold uppercase tracking-[0.2em]">Tiket Terdekat</flux:text>
                                <flux:heading size="lg" class="text-white truncate mt-1 leading-tight font-bold">
                                    {{ $dashboard['nextBooking']->showtime->movie->title }}
                                </flux:heading>
                                
                                <div class="mt-4 grid grid-cols-2 gap-2 text-xs border-t border-white/20 pt-4">
                                    <div>
                                        <p class="text-orange-200">Studio</p>
                                        <p class="font-bold">{{ $dashboard['nextBooking']->showtime->studio->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-orange-200">Waktu</p>
                                        <p class="font-bold">{{ \Carbon\Carbon::parse($dashboard['nextBooking']->showtime->start_time)->format('H:i') }}</p>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    @if($dashboard['nextBooking']->status === 'pending')
                                        <flux:button href="{{ route('booking.checkout', $dashboard['nextBooking']->id) }}" size="sm" class="w-full bg-white text-orange-600 hover:bg-orange-50 border-none font-bold" wire:navigate>
                                            Bayar Sekarang
                                        </flux:button>
                                    @else
                                        <flux:button href="{{ route('booking.history') }}" size="sm" class="w-full bg-orange-500 text-white hover:bg-orange-400 border-none font-bold" wire:navigate>
                                            Lihat Tiket
                                        </flux:button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="relative z-10 py-2 text-center">
                                <flux:text class="text-orange-100 italic">Belum ada jadwal nonton terdekat.</flux:text>
                            </div>
                        @endif
                    </flux:card>
                </div>
            </section>
        @endif
    @endauth

    {{-- Section: Now Showing --}}
    <section id="now-showing">
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <div class="h-8 w-1.5 bg-orange-600 rounded-full"></div>
                <div>
                    <flux:heading size="xl" class="font-black tracking-tight uppercase">Sedang Tayang</flux:heading>
                    <flux:text class="text-zinc-500">Film-film terbaik minggu ini.</flux:text>
                </div>
            </div>
            <flux:button href="#now-showing" variant="subtle" size="sm" class="rounded-full border-zinc-200 dark:border-zinc-800 uppercase tracking-widest text-[10px] font-bold">
                Lihat Semua
            </flux:button>
        </div>
        
        @if($movies->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-y-12 gap-x-6">
                @foreach($movies as $movie)
                    <div class="group cursor-pointer">
                        {{-- Movie Poster --}}
                        <div class="relative aspect-[2/3] rounded-3xl overflow-hidden shadow-xl bg-zinc-900 transition-all duration-500 group-hover:scale-[1.02] group-hover:shadow-orange-500/10">
                            @if($movie->poster_url)
                                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 group-hover:rotate-1">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-zinc-100 dark:bg-zinc-800">
                                    <flux:icon.film class="size-12 text-zinc-400" />
                                </div>
                            @endif

                            {{-- Dynamic Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-transparent to-transparent opacity-60 group-hover:opacity-90 transition-opacity"></div>
                            
                            {{-- Hover Actions --}}
                            <div class="absolute inset-0 flex flex-col justify-end p-4 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <flux:button href="{{ route('movie.show', $movie->id) }}" variant="primary" class="w-full bg-orange-600 hover:bg-orange-500 border-none font-bold shadow-lg shadow-orange-900/40" wire:navigate>
                                    Beli Tiket
                                </flux:button>
                            </div>

                            {{-- Duration Badge --}}
                            <div class="absolute top-4 left-4">
                                <span class="px-2 py-1 text-[9px] font-black bg-white/10 backdrop-blur-md text-white rounded-lg border border-white/20 uppercase">
                                    {{ $movie->duration }} Min
                                </span>
                            </div>
                        </div>
                        
                        {{-- Movie Info --}}
                        <div class="mt-5 space-y-1">
                            <flux:heading size="md" class="font-bold leading-tight truncate group-hover:text-orange-500 transition-colors">
                                {{ $movie->title }}
                            </flux:heading>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-tighter">Action • Drama</span>
                                <span class="size-1 bg-zinc-300 rounded-full"></span>
                                <span class="text-[10px] font-bold text-orange-500">HD</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-zinc-50 dark:bg-zinc-900/30 border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-[2rem] py-24 text-center">
                <flux:icon.film class="size-16 mx-auto text-zinc-300 mb-4" />
                <flux:heading size="lg" class="text-zinc-400 font-medium">Belum ada film yang tersedia.</flux:heading>
            </div>
        @endif
    </section>

    {{-- Section: Coming Soon --}}
    @if($upcomingMovies->count() > 0)
        <section class="relative bg-zinc-900 -mx-4 px-6 py-16 sm:mx-0 sm:px-12 sm:rounded-[3rem] overflow-hidden">
            {{-- Background Decoration --}}
            <div class="absolute top-0 right-0 size-64 bg-orange-600/20 blur-[100px] rounded-full"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-10">
                    <span class="px-3 py-1 bg-orange-600 text-[10px] font-black text-white rounded-full uppercase tracking-widest animate-pulse">Soon</span>
                    <flux:heading size="xl" class="text-white font-black uppercase tracking-tight">Segera Hadir</flux:heading>
                </div>
                
                <div class="flex gap-6 overflow-x-auto pb-6 no-scrollbar">
                    @foreach($upcomingMovies as $movie)
                        <div class="min-w-[280px] group cursor-pointer">
                            <div class="relative aspect-video mb-4 overflow-hidden rounded-2xl bg-zinc-800 ring-1 ring-white/10">
                                <div class="absolute inset-0 bg-zinc-950/40 group-hover:bg-transparent transition-colors duration-500"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                     <flux:icon.play class="size-10 text-white/50 group-hover:text-orange-500 group-hover:scale-125 transition-all duration-500" />
                                </div>
                            </div>
                            <flux:heading size="sm" class="text-white font-bold truncate group-hover:text-orange-400 transition-colors">{{ $movie->title }}</flux:heading>
                            <flux:text class="text-xs text-zinc-500 mt-1">Nantikan di bioskop kesayangan Anda.</flux:text>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Section: Cinemas --}}
    @if($cinemas->count() > 0)
        <section id="our-cinemas">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <flux:heading size="xl" class="font-black uppercase tracking-tight">Lokasi Bioskop</flux:heading>
                    <flux:text class="text-zinc-500">Cari lokasi yang paling dekat denganmu.</flux:text>
                </div>
                <flux:button href="#our-cinemas" variant="subtle" class="!bg-transparent !border-none !text-orange-500 font-bold uppercase text-xs tracking-widest hover:!text-orange-600">
    Lihat Semua
</flux:button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($cinemas as $cinema)
                    <flux:card class="p-0 overflow-hidden border-none bg-zinc-50 dark:bg-zinc-900/50 hover:ring-2 hover:ring-orange-500/50 transition-all duration-300 group">
                        <div class="p-6 flex items-center gap-5">
                            <div class="size-14 flex-shrink-0 flex items-center justify-center bg-white dark:bg-zinc-800 shadow-sm rounded-2xl group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                                <flux:icon.building-office-2 class="size-7" />
                            </div>
                            <div class="min-w-0">
                                <flux:heading size="lg" class="font-bold truncate">{{ $cinema->name }}</flux:heading>
                                <p class="text-orange-600 dark:text-orange-500 text-xs font-black uppercase tracking-widest mb-1">{{ $cinema->city }}</p>
                                <flux:text class="text-xs text-zinc-500 line-clamp-1">{{ $cinema->address }}</flux:text>
                            </div>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        </section>
    @endif
</div>

<style>
    /* Utility for cleaner horizontal scroll */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>