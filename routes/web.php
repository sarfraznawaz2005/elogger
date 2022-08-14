<?php

use App\Actions\Optimize;
use App\Http\Livewire\Calculator\IndexCalculator;
use App\Http\Livewire\Dashboard\IndexDashboard;
use App\Http\Livewire\Entries\IndexEntries;
use App\Http\Livewire\Users\IndexUsers;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

// logs
Route::get('logs', [LogViewerController::class, 'index']);

Route::get('optimize', Optimize::class);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/', IndexDashboard::class)->name('dashboard');
    Route::get('entries', IndexEntries::class)->name('entries');
    Route::get('calculator', IndexCalculator::class)->name('calculator');
    Route::get('users', IndexUsers::class)->name('users');

});
