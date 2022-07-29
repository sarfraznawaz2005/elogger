<?php

use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Entries;
use App\Http\Livewire\Project;
use App\Http\Livewire\Users;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/project_hours/{projectId}', Project::class)->name('project_hours');
    Route::get('users', Users::class)->name('users');
    Route::get('entries', Entries::class)->name('entries');

});
