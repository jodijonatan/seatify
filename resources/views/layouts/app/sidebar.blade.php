<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            /* Custom Scrollbar untuk estetika modern */
            ::-webkit-scrollbar { width: 5px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #27272a; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #3f3f46; }

            /* Efek halus pada transisi sidebar */
            .flux-sidebar-item { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        </style>
    </head>
    <body class="min-h-screen bg-white dark:bg-[#09090b] text-zinc-900 dark:text-zinc-100 antialiased selection:bg-orange-500/30">
        
        {{-- Sidebar Utama --}}
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200/50 bg-zinc-50/50 dark:border-zinc-800/50 dark:bg-[#09090b]">
            <flux:sidebar.header class="px-6 py-4">
                <div class="flex items-center gap-3 group">
                    <div class="p-1 rounded-xl bg-gradient-to-tr from-orange-500 to-amber-400 group-hover:rotate-12 transition-transform duration-300">
                        <x-app-logo :sidebar="true" class="invert dark:invert-0 scale-90" href="{{ auth()->user()?->hasRole('Admin') ? route('admin.dashboard') : route('home') }}" wire:navigate />
                    </div>
                    <flux:sidebar.collapse class="lg:hidden ml-auto hover:bg-zinc-200 dark:hover:bg-zinc-800 rounded-lg" />
                </div>
            </flux:sidebar.header>

            <flux:sidebar.nav class="px-3">
                <flux:sidebar.group :heading="__('General')" class="text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-400 dark:text-zinc-500 mt-6 mb-2 px-3">
                    <flux:sidebar.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate class="rounded-xl">
                        {{ __('Home') }}
                    </flux:sidebar.item>

                    @auth
                        <flux:sidebar.item icon="ticket" :href="route('booking.history')" :current="request()->routeIs('booking.history')" wire:navigate class="rounded-xl">
                            {{ __('My Bookings') }}
                        </flux:sidebar.item>
                    @endauth
                </flux:sidebar.group>

                @auth
                    @role('Admin')
                        <flux:sidebar.group :heading="__('Management')" class="text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-400 dark:text-zinc-500 mt-8 mb-2 px-3 border-t border-zinc-200/50 dark:border-zinc-800/50 pt-6">
                            <flux:sidebar.item icon="presentation-chart-bar" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate class="rounded-xl">
                                {{ __('Analytics') }}
                            </flux:sidebar.item>
                            
                            <flux:sidebar.item icon="building-office-2" :href="route('admin.cinemas.index')" :current="request()->routeIs('admin.cinemas.*')" wire:navigate class="rounded-xl">
                                {{ __('Cinemas') }}
                            </flux:sidebar.item>
                            
                            <flux:sidebar.item icon="film" :href="route('admin.movies.index')" :current="request()->routeIs('admin.movies.*')" wire:navigate class="rounded-xl">
                                {{ __('Movies') }}
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="calendar-days" :href="route('admin.showtimes.index')" :current="request()->routeIs('admin.showtimes.*')" wire:navigate class="rounded-xl">
                                {{ __('Showtimes') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @endrole
                @endauth
            </flux:sidebar.nav>

            <flux:spacer />

            {{-- User Menu di Bagian Bawah Sidebar (Desktop) --}}
            @auth
                <div class="hidden lg:block p-4 border-t border-zinc-200/50 dark:border-zinc-800/50">
                    <x-desktop-user-menu :name="auth()->user()->name" />
                </div>
            @endauth
        </flux:sidebar>

        {{-- Header Mobile (Hanya muncul di layar kecil) --}}
        <flux:header class="lg:hidden sticky top-0 z-40 bg-white/80 dark:bg-[#09090b]/80 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-800 px-4">
            <flux:sidebar.toggle icon="bars-2" inset="left" class="hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg" />
            
            <flux:spacer />

            @auth
                <flux:dropdown position="top" align="end">
                    <div class="flex items-center gap-2 p-1 pl-3 rounded-full border border-zinc-200 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-colors cursor-pointer">
                        <span class="text-xs font-medium">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        <flux:avatar size="xs" :name="auth()->user()->name" :initials="auth()->user()->initials()" class="rounded-full" />
                    </div>

                    <flux:menu class="w-56 p-2">
                        <div class="flex items-center gap-3 px-2 py-3 mb-2 bg-zinc-50 dark:bg-zinc-900 rounded-xl">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                            <div class="flex flex-col">
                                <flux:heading size="sm" class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text size="xs" class="truncate opacity-70">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>

                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>Settings</flux:menu.item>
                        <flux:menu.separator />
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-red-500 hover:text-red-600">
                                {{ __('Log out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <flux:button href="{{ route('login') }}" variant="subtle" size="sm" class="rounded-full shadow-sm hover:shadow-md transition-all">
                    Login
                </flux:button>
            @endauth
        </flux:header>

        {{-- Main Content Slot --}}
        <flux:main>
            {{-- Background decorative blobs (Optional) --}}
            <div class="absolute top-0 right-0 -z-10 h-[500px] w-[500px] bg-orange-500/5 blur-[120px] rounded-full pointer-events-none"></div>
            
            <div class="max-w-[1600px] mx-auto">
                {{ $slot }}
            </div>
        </flux:main>

        @fluxScripts
    </body>
</html>