<?php

use App\Http\Livewire\Calculator\IndexCalculator;
use App\Http\Livewire\Dashboard\IndexDashboard;
use App\Http\Livewire\Entries\IndexEntries;
use App\Http\Livewire\Users\IndexUsers;
use Illuminate\Support\Facades\Route;
use Livewire\Controllers\HttpConnectionHandler;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

// logs
Route::get('logs', [LogViewerController::class, 'index']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/', IndexDashboard::class)->name('dashboard');
    Route::get('entries', IndexEntries::class)->name('entries');
    Route::get('calculator', IndexCalculator::class)->name('calculator');
    Route::get('users', IndexUsers::class)->name('users');

    // https://stackoverflow.com/questions/69553897/laravel-livewire-how-to-customize-the-global-message-url
    Route::post('livewire/message/{name}', [HttpConnectionHandler::class, '__invoke']);

});
