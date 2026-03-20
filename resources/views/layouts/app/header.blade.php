<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            /* Smooth transitions untuk hover effects */
            .flux-navbar-item, .flux-sidebar-item {
                transition: all 0.2s ease-in-out;
            }
            /* Custom glassmorphism untuk header */
            .glass-header {
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
        </style>
    </head>
    <body class="min-h-screen bg-white dark:bg-[#09090b] text-zinc-900 dark:text-zinc-100 antialiased font-sans">
        
        {{-- Header Utama --}}
        <flux:header container class="glass-header sticky top-0 z-50 border-b border-zinc-200/50 bg-white/80 dark:border-zinc-800/50 dark:bg-[#09090b]/80">
            <flux:sidebar.toggle class="lg:hidden mr-4 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-xl" icon="bars-2" inset="left" />

            {{-- Logo dengan sentuhan branding --}}
            <div class="flex items-center gap-2 group">
                <x-app-logo href="{{ auth()->user()?->hasRole('Admin') ? route('admin.dashboard') : route('home') }}" wire:navigate class="transition-transform group-hover:scale-105" />
            </div>

            {{-- Desktop Navbar: Admin Only --}}
            @role('Admin')
            <flux:navbar class="ml-8 max-lg:hidden">
                <flux:navbar.item 
                    icon="presentation-chart-bar" 
                    :href="route('admin.dashboard')" 
                    :current="request()->routeIs('admin.dashboard')" 
                    class="font-semibold uppercase tracking-wider text-[11px]"
                    wire:navigate
                >
                    {{ __('Admin Panel') }}
                </flux:navbar.item>
            </flux:navbar>
            @endrole

            <flux:spacer />

            {{-- Action Icons --}}
            <flux:navbar class="items-center space-x-1 py-0!">
                <flux:tooltip :content="__('Cari Film...')" position="bottom">
                    <flux:navbar.item class="!h-9 !w-9 justify-center rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800" icon="magnifying-glass" href="#" />
                </flux:tooltip>
                
                <div class="h-4 w-px bg-zinc-200 dark:bg-zinc-800 mx-2 max-lg:hidden"></div>

                <flux:tooltip :content="__('GitHub Repository')" position="bottom">
                    <flux:navbar.item
                        class="h-9 w-9 justify-center rounded-xl max-lg:hidden hover:bg-zinc-100 dark:hover:bg-zinc-800"
                        icon="folder-git-2"
                        href="https://github.com/laravel/livewire-starter-kit"
                        target="_blank"
                    />
                </flux:tooltip>

                <flux:tooltip :content="__('Documentation')" position="bottom">
                    <flux:navbar.item
                        class="h-9 w-9 justify-center rounded-xl max-lg:hidden hover:bg-zinc-100 dark:hover:bg-zinc-800"
                        icon="book-open-text"
                        href="https://laravel.com/docs/starter-kits#livewire"
                        target="_blank"
                    />
                </flux:tooltip>
            </flux:navbar>

            {{-- User Profile Menu --}}
            <div class="ml-4 pl-4 border-l border-zinc-200 dark:border-zinc-800">
                <x-desktop-user-menu />
            </div>
        </flux:header>

        {{-- Mobile Sidebar --}}
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-white dark:border-zinc-800 dark:bg-[#09090b]">
            <flux:sidebar.header class="px-6 pt-6">
                <x-app-logo :sidebar="true" href="{{ auth()->user()?->hasRole('Admin') ? route('admin.dashboard') : route('home') }}" wire:navigate />
                <flux:sidebar.collapse class="-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="px-4 mt-8">
                <flux:sidebar.group :heading="__('Navigation')" class="text-[10px] uppercase tracking-[0.2em] font-black text-zinc-400 dark:text-zinc-500 mb-4">
                    <flux:sidebar.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
                        {{ __('Beranda') }}
                    </flux:sidebar.item>
                    
                    @role('Admin')
                        <flux:sidebar.item icon="presentation-chart-bar" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>
                            {{ __('Admin Dashboard') }}
                        </flux:sidebar.item>
                    @endrole
                    
                    <flux:sidebar.item icon="ticket" :href="route('booking.history')" :current="request()->routeIs('booking.history')" wire:navigate>
                        {{ __('Tiket Saya') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Resources')" class="mt-8 text-[10px] uppercase tracking-[0.2em] font-black text-zinc-400 dark:text-zinc-500 mb-4">
                    <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                        {{ __('Repository') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                        {{ __('Documentation') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />
            
            <div class="p-4 bg-zinc-50 dark:bg-zinc-900/50 m-4 rounded-2xl">
                <flux:text size="xs" class="text-center italic text-zinc-500">v1.0.0 — Crafted with Passion</flux:text>
            </div>
        </flux:sidebar>

        {{-- Main Content Slot --}}
        <flux:main container>
            <div class="py-10">
                {{ $slot }}
            </div>
        </flux:main>

        {{-- Footer Ringkas (Opsional) --}}
        <footer class="border-t border-zinc-200 dark:border-zinc-800 py-8">
            <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <flux:text class="text-xs text-zinc-500">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</flux:text>
                <div class="flex gap-6">
                    <a href="#" class="text-xs text-zinc-400 hover:text-orange-500 transition-colors">Privacy Policy</a>
                    <a href="#" class="text-xs text-zinc-400 hover:text-orange-500 transition-colors">Terms of Service</a>
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>