<?php

use App\Models\User;
use function Pest\Laravel\{actingAs, get};

test('dashboard is accessible', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSeeLivewire(App\Livewire\Frontend\Dashboard::class);
});

test('cinemas page is accessible', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('cinemas.index'))
        ->assertOk()
        ->assertSeeLivewire(App\Livewire\Frontend\Cinema\Index::class);
});

test('studios page is accessible', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('studios.index'))
        ->assertOk()
        ->assertSeeLivewire(App\Livewire\Frontend\Studio\Index::class);
});

test('movies page is accessible', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('movies.index'))
        ->assertOk()
        ->assertSeeLivewire(App\Livewire\Frontend\Movie\Index::class);
});
