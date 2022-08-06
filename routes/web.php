<?php

use App\Http\Livewire\Dashboard\IndexDashboard;
use App\Http\Livewire\Entries\IndexEntries;
use App\Http\Livewire\Users\IndexUsers;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/', IndexDashboard::class)->name('dashboard');
    Route::get('entries', IndexEntries::class)->name('entries');
    Route::get('users', IndexUsers::class)->name('users');

});
