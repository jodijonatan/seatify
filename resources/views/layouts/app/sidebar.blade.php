<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ auth()->user()?->hasRole('Admin') ? route('admin.dashboard') : route('home') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
                        {{ __('Home') }}
                    </flux:sidebar.item>

                    @role('Admin')
                        <flux:sidebar.item icon="presentation-chart-bar" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>
                            {{ __('Dashboard') }}
                        </flux:sidebar.item>
                    @endrole

                    <flux:sidebar.item icon="ticket" :href="route('booking.history')" :current="request()->routeIs('booking.history')" wire:navigate>
                        {{ __('My Bookings') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                @auth
                    @role('Admin')
                        <flux:sidebar.group :heading="__('Admin Management')" class="grid">
                            <flux:sidebar.item icon="building-office-2" :href="route('admin.cinemas.index')" :current="request()->routeIs('admin.cinemas.*')" wire:navigate>
                                {{ __('Cinemas') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="presentation-chart-bar" :href="route('admin.studios.index')" :current="request()->routeIs('admin.studios.*')" wire:navigate>
                                {{ __('Studios') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="film" :href="route('admin.movies.index')" :current="request()->routeIs('admin.movies.*')" wire:navigate>
                                {{ __('Movies') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="calendar-days" :href="route('admin.showtimes.index')" :current="request()->routeIs('admin.showtimes.*')" wire:navigate>
                                {{ __('Showtimes') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="ticket" :href="route('admin.bookings.index')" :current="request()->routeIs('admin.bookings.*')" wire:navigate>
                                {{ __('Bookings') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @endrole
                @endauth
            </flux:sidebar.nav>

            @auth
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            @endauth
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            @auth
                <flux:dropdown position="top" align="end">
                    <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

                    <flux:menu>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>

                        <flux:menu.separator />

                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer"
                            >
                                {{ __('Log out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <flux:button href="{{ route('login') }}" variant="ghost" icon="user-circle">Log in</flux:button>
            @endauth
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>