<?php

use App\Http\Livewire\Dashboard\DashboardIndex;
use App\Http\Livewire\Entries\EntriesIndex;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/', DashboardIndex::class)->name('dashboard');
    Route::get('entries', EntriesIndex::class)->name('entries');

});
